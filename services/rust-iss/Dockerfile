# Stage 1: Build
FROM rust:latest as builder

WORKDIR /app

# Копируем ТОЛЬКО Cargo.toml
COPY Cargo.toml ./

# Принудительно удаляем Cargo.lock, чтобы гарантировать его пересоздание.
RUN rm -f Cargo.lock

# Создаем пустышку. Теперь cargo build сгенерирует Cargo.lock
RUN mkdir src && \
    echo "fn main(){}" > src/main.rs && \
    cargo build --release

# Копируем остальной код
COPY src ./src

# Собираем финальный билд
RUN touch src/main.rs && \
    cargo build --release

# Stage 2: Runtime
FROM debian:bookworm-slim
WORKDIR /app

# Устанавливаем недостающие библиотеки OpenSSL
RUN apt-get update && apt-get install -y --no-install-recommends libssl3 ca-certificates && rm -rf /var/lib/apt/lists/*

COPY --from=builder /app/target/release/rust_iss /app/rust_iss

CMD ["./rust_iss"]