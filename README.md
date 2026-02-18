# Direct Apple Pay on Web -- Demo Integration

It demonstrates how merchants can securely accept Apple Pay payments
directly from their website.

------------------------------------------------------------------------
## 📋 Requirements

Before running the demo, ensure you have:

-   Apple Developer Account
-   Apple Pay Merchant ID
-   Payment Processing Certificate
-   Merchant Processing Certificate
-   Verified domain for Apple Pay
------------------------------------------------------------------------

## 🛠 Technical Stack

-   Frontend: HTML / JavaScript
-   Backend: PHP (or any server-side language)
-   Apple Pay JS API
-   
------------------------------------------------------------------------

### 2️⃣ Configure Merchant Details

Update your configuration file with:

``` php
define('PRODUCTION_CERTIFICATE_KEY', '');   // .key.pem file path
define('PRODUCTION_CERTIFICATE_PATH', '');  // .crt.pem file path
define('PRODUCTION_CERTIFICATE_KEY_PASS', '');  // password for certificates
DOMAIN_NAME = "yourdomain.com";
define('TERMINAL_ID','');
define('PASSWORD','');
define('KEY', ''); 
define('URL', ''); 
define('DEBUG', '');
```
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

Apple Pay Web Certificate Creation Guide

This guide explains the complete process to configure Apple Pay on Web,
including Merchant ID creation, domain verification, and certificate
generation.

------------------------------------------------------------------------

## A. Create Apple Developer Account

1.  Go to the Apple Developer website.
2.  Create an Apple Developer account if you do not already have one.
3.  Complete enrollment if required.

------------------------------------------------------------------------

## B. Create Apple Pay Merchant ID

1.  Sign in to your Apple Developer account.
2.  Navigate to Certificates, Identifiers & Profiles.
3.  Click Identifiers → + (Create New).
4.  Select Merchant ID.
5.  Enter:
    -   Description (e.g., Your Company Apple Pay)
    -   Merchant Identifier (e.g., merchant.com.yourcompany)
6.  Click Register.

------------------------------------------------------------------------

## C. Verify Your Domain (Web Only)

1.  Open your created Merchant ID.
2.  Click Add Domain.
3.  Enter the domain or subdomain where Apple Pay will be implemented.
4.  Download the domain association file from the Apple Developer
    portal.
5.  Upload the file to:

https://yourdomain.com/.well-known/apple-developer-merchantid-domain-association.txt

6.  Return to Apple Developer portal and click Verify.
7.  Ensure the domain status shows Verified.

### If Status Shows "Pending"

-   Confirm HTTPS (valid TLS/SSL certificate).
-   Ensure file path is correct.
-   Ensure file content is unchanged.
-   Confirm no redirects are interfering.

------------------------------------------------------------------------

## D. Create Merchant Identity Certificate & Private Key (Web Only)

### Step 1: Generate CSR (Mac)

1.  Open Keychain Access.
2.  Click Certificate Assistant → Request a Certificate From a
    Certificate Authority.
3.  Enter your email address and Common Name.
4.  Select Saved to disk.
5.  Save the CSR file.

### Step 2: Create Certificate in Apple Developer Portal

1.  Go to Merchant ID.
2.  Under Apple Pay Merchant Identity Certificate, click Create
    Certificate.
3.  Upload the CSR file.
4.  Download the generated .cer file.
5.  Double-click to install in Keychain.

### Step 3: Export Private Key

1.  Open Keychain Access → My Certificates.
2.  Locate your certificate and expand it.
3.  Right-click the private key.
4.  Select Export.
5.  Save as .p12.
6.  Set an export password.

### Step 4: Convert Certificate to PEM

openssl pkcs12 -in Certificates.p12 -out ApplePay.crt.pem -clcerts
-nokeys

### Step 5: Create Private Key in PEM Format

openssl pkcs12 -in Certificates.p12 -out ApplePay.key.pem -nocerts

------------------------------------------------------------------------

## E. Create Payment Processing Certificate

### Step 1: Generate CSR (ECC 256)

1.  Open Keychain Access.
2.  Certificate Assistant → Request a Certificate From a Certificate
    Authority.
3.  Enter email and Common Name.
4.  Select Saved to disk and Let me specify key pair information.
5.  Choose:
    -   Algorithm: ECC
    -   Key Size: 256 bits

### Step 2: Create Certificate in Apple Portal

1.  Go to Merchant ID.
2.  Select Apple Pay Payment Processing Certificate.
3.  Click Create Certificate.
4.  Upload CSR file.
5.  Download the generated applepay.cer.
6.  Install certificate in Keychain.

### Step 3: Export Private Key

1.  Export private key as .p12.
2.  Set password.

### Step 4: Convert .p12 to PEM

openssl pkcs12 -in Certificates.p12 -passin pass:YOURPASSWORD -out
Certificates.pem

### Step 5: Convert PEM to PK8 Format

openssl pkcs8 -in ecc.pem -topk8 -nocrypt -out ecc.pk8

### Step 6: Rename File

Rename the generated .pk8 file using your Merchant Identifier name.

Example:

merchant.com.yourcompany.pk8

------------------------------------------------------------------------

## Final Files Required

-   Merchant Identity Certificate (PEM)
-   Merchant Identity Private Key (PEM)
-   Payment Processing Certificate
-   Private Key (.pk8)
-   Verified Domain

------------------------------------------------------------------------

## Security Notes

-   Keep .p12, .pem, and .pk8 files secure.
-   Never expose private keys publicly.
-   Use strong passwords for exports.
-   Store certificates securely on production servers.
------------------------------------------------------------------------

## 📄 License

This project is provided for demo and integration reference purposes
only.
