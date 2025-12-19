import pandas as pd
import os
import sys

csv_path = 'legacy_export.csv'
xlsx_path = 'legacy_export.xlsx'

def convert_to_excel():
    if not os.path.exists(csv_path):
        print(f"Error: {csv_path} not found.")
        return

    print("Python Converter: Reading CSV...")
    
    try:
        df = pd.read_csv(csv_path, sep=';', encoding='utf-8')
        
        df.to_excel(xlsx_path, index=False)
        
        print(f"Python Converter: Successfully created {xlsx_path}")
    except Exception as e:
        print(f"Python Converter Error: {e}")

if __name__ == "__main__":
    convert_to_excel()