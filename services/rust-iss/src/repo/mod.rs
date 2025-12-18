use sqlx::{PgPool, query}; // Импортируем функцию query
use std::sync::Arc;
use serde_json::Value;

pub struct IssRepo {
    pool: Arc<PgPool>,
}

impl IssRepo {
    pub fn new(pool: Arc<PgPool>) -> Self {
        Self { pool }
    }

    // Используем обычный query (без !), чтобы избежать проверки в compile-time
    pub async fn insert_fetch_log(&self, source_url: &str, payload: &Value) -> Result<(), sqlx::Error> {
        query("INSERT INTO iss_fetch_log (source_url, payload) VALUES ($1, $2)")
            .bind(source_url)
            .bind(payload)
            .execute(&*self.pool)
            .await?;

        Ok(())
    }
}