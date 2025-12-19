use axum::{Json};
use serde_json::{Value, json};

pub async fn get_iss_data() -> Json<Value> {
    Json(json!({ "status": "OK", "message": "Data is fetched by PHP frontend directly from DB" }))
}

pub async fn health_check() -> &'static str {
    "OK"
}