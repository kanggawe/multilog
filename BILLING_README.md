# Sistem Billing Internet PPPoE

## Overview
Sistem billing internet dengan dukungan PPPoE (Point-to-Point Protocol over Ethernet) yang terintegrasi dengan Laravel. Sistem ini menyediakan manajemen pelanggan, paket internet, akun PPPoE, dan sistem billing yang lengkap.

## Fitur Utama

### 1. Manajemen Customer
- CRUD customer dengan informasi lengkap
- Kode customer otomatis (CUST0001, CUST0002, dst)
- Status customer (Active, Inactive, Suspended)
- Sistem deposit pelanggan
- Pencarian dan filter customer

### 2. Manajemen Paket Internet
- Paket internet dengan bandwidth up/down
- Harga dan billing cycle (monthly, quarterly, yearly)
- IP pool management
- Features tambahan (JSON format)

### 3. Manajemen Akun PPPoE
- Username dan password otomatis
- Integrasi dengan Mikrotik RouterOS (TODO)
- Status akun (Active, Inactive, Suspended, Expired)
- Monitoring usage dan statistik
- Static IP support

### 4. Sistem Billing
- Generate invoice otomatis
- Multiple payment methods
- Laporan keuangan
- Dashboard dengan statistik real-time
- Alert untuk invoice overdue dan akun expired

## Database Schema

### Customers Table
```sql
- id (Primary Key)
- customer_code (Unique)
- name
- address
- phone
- email
- id_card
- status (enum: active, inactive, suspended)
- join_date
- deposit (decimal)
- notes
- created_by (Foreign Key to users)
- timestamps
```

### Internet Packages Table
```sql
- id (Primary Key)
- name
- description
- bandwidth_up (kbps)
- bandwidth_down (kbps)
- price (decimal)
- billing_cycle (enum: monthly, quarterly, yearly)
- is_active (boolean)
- duration_days
- ip_pool
- features (JSON)
- timestamps
```

### PPPoE Accounts Table
```sql
- id (Primary Key)
- customer_id (Foreign Key)
- internet_package_id (Foreign Key)
- username (Unique)
- password
- static_ip
- profile_name (Mikrotik profile)
- status (enum: active, inactive, suspended, expired)
- last_login
- last_ip
- bytes_in
- bytes_out
- expires_at
- notes
- timestamps
```

### Subscriptions Table
```sql
- id (Primary Key)
- customer_id (Foreign Key)
- internet_package_id (Foreign Key)
- start_date
- end_date
- monthly_fee (decimal)
- status (enum: active, inactive, suspended, expired)
- auto_renew (boolean)
- notes
- timestamps
```

### Invoices Table
```sql
- id (Primary Key)
- invoice_number (Unique, auto-generated)
- customer_id (Foreign Key)
- subscription_id (Foreign Key)
- invoice_date
- due_date
- amount (decimal)
- paid_amount (decimal)
- status (enum: unpaid, partial, paid, overdue)
- description
- notes
- timestamps
```

### Payments Table
```sql
- id (Primary Key)
- payment_number (Unique, auto-generated)
- customer_id (Foreign Key)
- invoice_id (Foreign Key)
- amount (decimal)
- method (enum: cash, bank_transfer, e_wallet, credit_card)
- payment_date
- reference_number
- notes
- received_by (Foreign Key to users)
- timestamps
```

## API Routes

### Customer Management
```
GET    /customers              - List customers
POST   /customers              - Create customer
GET    /customers/{id}         - Show customer detail
PUT    /customers/{id}         - Update customer
DELETE /customers/{id}         - Delete customer
```

### Package Management
```
GET    /packages               - List packages
POST   /packages               - Create package
GET    /packages/{id}          - Show package detail
PUT    /packages/{id}          - Update package
DELETE /packages/{id}          - Delete package
```

### PPPoE Management
```
GET    /pppoe                  - List PPPoE accounts
POST   /pppoe                  - Create PPPoE account
GET    /pppoe/{id}             - Show account detail
PUT    /pppoe/{id}             - Update account
DELETE /pppoe/{id}             - Delete account
POST   /pppoe/{id}/enable      - Enable account
POST   /pppoe/{id}/disable     - Disable account
```

### Billing Management
```
GET    /billing/dashboard      - Dashboard billing
POST   /billing/generate-invoices - Generate monthly invoices
GET    /billing/reports/financial - Financial reports
GET    /billing/reports/customers - Customer reports
```

## Integrasi Mikrotik RouterOS

Sistem ini dirancang untuk terintegrasi dengan Mikrotik RouterOS menggunakan API. Untuk mengaktifkan integrasi:

### 1. Install RouterOS API Library
```bash
composer require routeros/routeros-api-php
```

### 2. Konfigurasi Environment
```env
MIKROTIK_HOST=192.168.1.1
MIKROTIK_USERNAME=admin
MIKROTIK_PASSWORD=password
MIKROTIK_PORT=8728
```

### 3. Implementasi Service
Buat service untuk handle Mikrotik API:
```php
php artisan make:service MikrotikService
```

## Installation & Setup

### 1. Clone Repository
```bash
git clone <repository-url>
cd multilog
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 5. Run Application
```bash
php artisan serve
npm run dev
```

## Default Data

Setelah menjalankan seeder, sistem akan memiliki:

### Sample Internet Packages:
1. Paket Starter 2 Mbps - Rp 99,000
2. Paket Home 5 Mbps - Rp 150,000
3. Paket Home 10 Mbps - Rp 250,000
4. Paket Business 20 Mbps - Rp 500,000
5. Paket Enterprise 50 Mbps - Rp 1,200,000

### Sample Customers:
1. Budi Santoso (Active)
2. Siti Nurhaliza (Active)
3. Ahmad Wijaya (Active)
4. Dewi Sartika (Suspended)
5. Rini Susanti (Active)

## Usage Guide

### 1. Menambah Customer Baru
1. Akses menu "Customer" > "Tambah Customer"
2. Isi informasi customer lengkap
3. Kode customer akan di-generate otomatis

### 2. Membuat Akun PPPoE
1. Akses menu "PPPoE" > "Tambah Akun"
2. Pilih customer dan paket internet
3. Username dan password akan di-generate otomatis
4. Akun siap untuk di-sync ke Mikrotik

### 3. Generate Invoice Bulanan
1. Akses "Dashboard Billing"
2. Klik "Generate Invoice Bulanan"
3. Invoice akan dibuat untuk semua customer aktif

### 4. Monitoring dan Reporting
- Dashboard menyediakan statistik real-time
- Alert untuk invoice overdue dan akun expired
- Laporan keuangan dan customer growth

## TODO Features

### High Priority
1. **Mikrotik RouterOS Integration**
   - Auto create/update/delete PPPoE secrets
   - Real-time bandwidth monitoring
   - Session management

2. **Payment Gateway Integration**
   - Virtual Account
   - E-wallet (QRIS, OVO, GoPay, etc)
   - Credit card processing

3. **Automated Billing**
   - Auto-suspend overdue accounts
   - Payment reminders via WhatsApp/SMS
   - Auto-renew subscriptions

### Medium Priority
4. **Customer Portal**
   - Login portal untuk customer
   - Invoice dan payment history
   - Bandwidth usage monitoring
   - Support ticket system

5. **Advanced Reporting**
   - Revenue forecasting
   - Churn analysis
   - Bandwidth utilization reports
   - Export to PDF/Excel

### Low Priority
6. **Mobile App**
   - Admin mobile app
   - Customer mobile app
   - Push notifications

7. **Advanced Features**
   - Multi-ISP support
   - Reseller management
   - API for third-party integration

## Support & Maintenance

### Database Backup
```bash
# Daily backup
php artisan backup:run

# Clean old backups
php artisan backup:clean
```

### Monitoring
- Monitor database performance
- Check Mikrotik API connectivity
- Monitor payment gateway status
- Regular security updates

## Security Considerations

1. **Authentication & Authorization**
   - Multi-level user access (Admin, Manager, Staff)
   - Role-based permissions
   - Secure password policies

2. **Data Protection**
   - Customer data encryption
   - Payment data security
   - Regular security audits

3. **API Security**
   - Rate limiting
   - API authentication tokens
   - Input validation

## License

This project is proprietary software. Unauthorized distribution is prohibited.

## Contact

For support and inquiries, please contact the development team.