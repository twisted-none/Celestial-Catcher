use axum::{
    routing::get,
    Router,
};
use crate::config::AppState;
use crate::handlers::{get_iss_data, health_check};
use tower_http::trace::TraceLayer;
// Можно добавить более сложный RateLimit, но для начала базовый TraceLayer

pub fn create_router(state: AppState) -> Router {
    Router::new()
        .route("/api/iss", get(get_iss_data))
        .route("/health", get(health_check))
        .layer(TraceLayer::new_for_http()) 
        .with_state(state)
}