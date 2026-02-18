# 🍎 Direct Apple Pay on Web -- Demo Integration

This repository contains a demo implementation of **Direct Apple Pay on
Web** integration.\
It demonstrates how merchants can securely accept Apple Pay payments
directly from their website.

------------------------------------------------------------------------

## 🚀 Features

-   Apple Pay JavaScript API integration
-   Merchant validation handling
-   Secure payment token processing
-   Server-side payment authorization
-   Success & failure response handling
-   Sandbox testing support

------------------------------------------------------------------------

## 📋 Requirements

Before running the demo, ensure you have:

-   Apple Developer Account
-   Apple Pay Merchant ID
-   Payment Processing Certificate
-   Verified domain for Apple Pay
-   HTTPS-enabled server (Apple Pay requires SSL)
-   Safari browser (macOS or iOS)

------------------------------------------------------------------------

## 🛠 Technical Stack

-   Frontend: HTML / JavaScript
-   Backend: PHP (or any server-side language)
-   Apple Pay JS API
-   Payment Gateway API

------------------------------------------------------------------------

## ⚙️ Setup Instructions

### 1️⃣ Clone the Repository

``` bash
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>
```

------------------------------------------------------------------------

### 2️⃣ Configure Merchant Details

Update your configuration file with:

``` php
MERCHANT_IDENTIFIER = "your.merchant.id";
DISPLAY_NAME = "Your Store Name";
DOMAIN_NAME = "yourdomain.com";
PAYMENT_GATEWAY_ENDPOINT = "https://api.yourgateway.com";
API_KEY = "your_api_key";
SECRET_KEY = "your_secret_key";
```

------------------------------------------------------------------------

### 3️⃣ Upload Apple Pay Domain Verification File

Download the domain verification file from the Apple Developer Portal
and place it under:

    .well-known/apple-developer-merchantid-domain-association

Accessible via:

    https://yourdomain.com/.well-known/apple-developer-merchantid-domain-association

------------------------------------------------------------------------

## 💳 Apple Pay Payment Flow

1.  Customer clicks **Apple Pay** button\
2.  Apple Pay session starts\
3.  Merchant validation is performed\
4.  Customer authorizes payment (Face ID / Touch ID)\
5.  Encrypted payment token is generated\
6.  Token is sent to your server\
7.  Server processes transaction via Payment Gateway\
8.  Transaction result is returned to customer

------------------------------------------------------------------------

## 🔐 Security Notes

-   Never expose API secrets in frontend code\
-   Perform transaction authorization server-side\
-   Always use HTTPS\
-   Use sandbox credentials for testing

------------------------------------------------------------------------

## 🧪 Testing

-   Use Apple Pay Sandbox account\
-   Test on Safari browser\
-   Use sandbox merchant certificates

------------------------------------------------------------------------

## 📦 Example Project Structure

    /
     ├── index.html
     ├── applepay.js
     ├── payment_process.php
     ├── config.php
     ├── .well-known/
     └── README.md

------------------------------------------------------------------------

## 📄 License

This project is provided for demo and integration reference purposes
only.
