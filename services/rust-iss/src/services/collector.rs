use std::sync::Arc;
use std::time::Duration;
use tokio::time;
use crate::repo::IssRepo;
use crate::domain::IssApiResponse;
use sqlx::PgPool;
use serde_json::json;

pub async fn start_background_collector(pool: Arc<PgPool>) {
    let repo = IssRepo::new(pool);
    let mut interval = time::interval(Duration::from_secs(10));
    let source_url = "https://api.wheretheiss.at/v1/satellites/25544";

    loop {
        interval.tick().await;
        
        let fake_response = json!({
            "name": "iss",
            "id": 25544,
            "latitude": rand::random::<f64>() * 90.0 - 45.0,
            "longitude": rand::random::<f64>() * 360.0 - 180.0,
            "velocity": 27600.5,
            "visibility": "daylight",
            "timestamp": chrono::Utc::now().timestamp(),
        });

        match repo.insert_fetch_log(source_url, &fake_response).await {
            Ok(_) => tracing::info!("Background: ISS fetch log inserted successfully"),
            Err(e) => tracing::error!("Background: Failed to insert fetch log: {:?}", e),
        }
    }
}