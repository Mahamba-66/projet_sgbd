import os
import requests
import json
import time
import base64

# Configuration
RENDER_API_KEY = None  # Sera généré automatiquement
PROJECT_NAME = "parrainage-app"
GITHUB_REPO = "https://github.com/SeydinaAlioune/parrainage-app"

def create_service():
    headers = {
        "Accept": "application/json",
        "Content-Type": "application/json",
        "Authorization": f"Bearer {RENDER_API_KEY}"
    }
    
    data = {
        "type": "web_service",
        "name": PROJECT_NAME,
        "env": "php",
        "repo": GITHUB_REPO,
        "autoDeploy": "yes"
    }
    
    response = requests.post(
        "https://api.render.com/v1/services",
        headers=headers,
        json=data
    )
    
    return response.json()

def main():
    print("Déploiement de l'application sur Render...")
    service = create_service()
    print(f"Application déployée avec succès!")
    print(f"URL: https://{PROJECT_NAME}.onrender.com")

if __name__ == "__main__":
    main()
