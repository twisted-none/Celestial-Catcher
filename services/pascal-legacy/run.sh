#!/bin/bash

echo "Starting Legacy Service Wrapper..."

while true; do
    echo "-----------------------------------"
    echo "[$(date)] Starting Job..."
    
    ./main
    
    python3 converter.py
    
    echo "Job finished. Waiting for next cycle (60s)..."
    
    sleep 60
done