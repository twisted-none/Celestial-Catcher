use std::env;
use std::sync::Arc;
use sqlx::postgres::PgPoolOptions;
use sqlx::PgPool;
use redis::Client;

#[derive(Clone)]
pub struct AppState {
    pub db: Arc<PgPool>,
    pub redis: Client,
}

pub async fn init_config() -> AppState {
    let db_url = env::var("DATABASE_URL").expect("DATABASE_URL must be set");
    let redis_url = env::var("REDIS_URL").expect("REDIS_URL must be set");
    
    let pool = PgPoolOptions::new()
        .max_connections(10)
        .connect(&db_url)
        .await
        .expect("Failed to connect to Postgres");

    let redis_client = Client::open(redis_url).expect("Invalid Redis URL");

    AppState {
        db: Arc::new(pool),
        redis: redis_client,
    }
}