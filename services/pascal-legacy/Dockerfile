FROM debian:12-slim

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        fp-compiler \
        python3 \
        python3-pip \
        bash && \
    rm -rf /var/lib/apt/lists/*

# ВАЖНО: разрешаем pip ставить пакеты в system Python
RUN pip3 install --no-cache-dir pandas openpyxl --break-system-packages

WORKDIR /app

COPY . .

RUN fpc main.pas
RUN chmod +x run.sh

CMD ["./run.sh"]
