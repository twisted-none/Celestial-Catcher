mod config;
mod domain;
mod handlers;
mod repo;
mod routes;
mod services;

use dotenv::dotenv;
use std::net::SocketAddr;

#[tokio::main]
async fn main() {
    // 1. Загрузка переменных окружения
    dotenv().ok();
    
    // 2. Инициализация логирования
    tracing_subscriber::fmt::init();

    // 3. Инициализация БД и Redis
    let state = config::init_config().await;

    // 4. Запуск фонового сборщика данных (в отдельном потоке)
    // Клонируем пул для передачи в задачу
    let db_pool_for_worker = state.db.clone();
    tokio::spawn(async move {
        services::collector::start_background_collector(db_pool_for_worker).await;
    });

    // 5. Запуск HTTP сервера
    let app = routes::create_router(state);
    let addr = SocketAddr::from(([0, 0, 0, 0], 3000));
    
    tracing::info!("🚀 Rust ISS Service started on {}", addr);
    
    let listener = tokio::net::TcpListener::bind(addr).await.unwrap();
    axum::serve(listener, app).await.unwrap();
}