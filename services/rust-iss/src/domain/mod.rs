use serde::{Deserialize, Serialize};

// Структура для сырого JSON ответа, который мы будем хранить
#[derive(Debug, Serialize, Deserialize)]
pub struct IssPosition {
    pub latitude: String,
    pub longitude: String,
}

#[derive(Debug, Serialize, Deserialize)]
pub struct IssApiResponse {
    pub message: String,
    pub iss_position: IssPosition,
    pub timestamp: i64,
}