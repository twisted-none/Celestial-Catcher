FROM rust:latest as builder

WORKDIR /app

COPY Cargo.toml ./

RUN rm -f Cargo.lock

RUN mkdir src && \
    echo "fn main(){}" > src/main.rs && \
    cargo build --release

COPY src ./src

RUN touch src/main.rs && \
    cargo build --release

FROM debian:bookworm-slim
WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends libssl3 ca-certificates && rm -rf /var/lib/apt/lists/*

COPY --from=builder /app/target/release/rust_iss /app/rust_iss

CMD ["./rust_iss"]