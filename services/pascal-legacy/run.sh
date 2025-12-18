#!/bin/bash

echo "Starting Legacy Service Wrapper..."

while true; do
    echo "-----------------------------------"
    echo "[$(date)] Starting Job..."
    
    # 1. Запуск скомпилированного Pascal приложения
    ./main
    
    # 2. Запуск Python конвертера для создания Excel
    python3 converter.py
    
    echo "Job finished. Waiting for next cycle (60s)..."
    
    # Ждем минуту перед следующим запуском
    sleep 60
done