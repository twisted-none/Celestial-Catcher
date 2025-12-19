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
    dotenv().ok();
    
    tracing_subscriber::fmt::init();

    let state = config::init_config().await;

    let db_pool_for_worker = state.db.clone();
    tokio::spawn(async move {
        services::collector::start_background_collector(db_pool_for_worker).await;
    });

    let app = routes::create_router(state);
    let addr = SocketAddr::from(([0, 0, 0, 0], 3000));
    
    tracing::info!("🚀 Rust ISS Service started on {}", addr);
    
    let listener = tokio::net::TcpListener::bind(addr).await.unwrap();
    axum::serve(listener, app).await.unwrap();
}