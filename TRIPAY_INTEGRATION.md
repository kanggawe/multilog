# Integrasi Payment Gateway Tripay

## Gambaran Umum
Sistem billing internet telah berhasil diintegrasikan dengan payment gateway Tripay untuk memungkinkan pembayaran online yang aman dan mudah.

## Fitur yang Diimplementasikan

### 1. ✅ Konfigurasi Tripay
- File konfigurasi: `config/tripay.php`
- Environment variables di `.env`
- Mendukung mode sandbox dan production
- Konfigurasi callback URL dan return URL

### 2. ✅ Service Layer (TripayService)
- **Lokasi**: `app/Services/TripayService.php`
- **Fitur**:
  - Get payment channels dari Tripay
  - Create transaction
  - Get transaction detail
  - Generate signature untuk keamanan
  - Verify callback signature
  - Process callback dari Tripay

### 3. ✅ Database Schema Updates
- **Migration**: `add_tripay_fields_to_payments_table`
- **Field baru**:
  - `merchant_ref` - Reference unik untuk merchant
  - `tripay_reference` - Reference dari Tripay
  - `payment_gateway` - Jenis gateway (manual/tripay)
  - `gateway_status` - Status dari gateway (PAID, PENDING, FAILED, dll)
  - `gateway_response` - Response JSON dari gateway
  - `fee_merchant` - Fee yang ditanggung merchant
  - `fee_customer` - Fee yang ditanggung customer
  - `payment_url` - URL pembayaran dari Tripay
  - `paid_at` - Waktu pembayaran berhasil
  - `expired_at` - Waktu expired pembayaran

### 4. ✅ Payment Model Updates
- **Lokasi**: `app/Models/Payment.php`
- **Method baru**:
  - `isGatewayPayment()` - Cek apakah menggunakan gateway
  - `isPaid()` - Cek status paid
  - `isPending()` - Cek status pending
  - `isExpired()` - Cek status expired
  - `isFailed()` - Cek status failed
  - `getStatusBadgeClass()` - Class CSS untuk badge
  - `getStatusText()` - Text status
  - `getTotalAmountAttribute()` - Total termasuk fee

### 5. ✅ Payment Gateway Controller
- **Lokasi**: `app/Http/Controllers/PaymentGatewayController.php`
- **Method**:
  - `showPaymentGateway()` - Tampilkan pilihan metode pembayaran
  - `processPayment()` - Proses pembayaran via Tripay
  - `paymentSuccess()` - Handle return dari Tripay
  - `callback()` - Handle webhook dari Tripay
  - `checkStatus()` - Cek status pembayaran
  - `updatePaymentFromTripayData()` - Update data dari Tripay

### 6. ✅ Routes
- **Payment Gateway Routes**:
  - `GET /billing/payments/{invoice}/gateway` - Pilih metode pembayaran
  - `POST /billing/payments/{invoice}/gateway` - Process pembayaran
  - `GET /billing/payments/success` - Halaman sukses
  - `GET /billing/payments/{payment}/check-status` - Cek status via AJAX
- **Webhook Route**:
  - `POST /api/tripay/callback` - Webhook dari Tripay (no auth)

### 7. ✅ Views
- **Payment Gateway Selection**: `resources/views/billing/payments/gateway.blade.php`
  - Tampilan pilihan metode pembayaran
  - Form input customer data
  - Tampilan fee dan total
  - JavaScript untuk interaktivitas
- **Payment Success**: `resources/views/billing/payments/success.blade.php`
  - Halaman konfirmasi pembayaran
  - Auto-refresh untuk status pending
  - Button manual check status
  - Link ke receipt dan invoice

### 8. ✅ UI Updates
- **Invoice Detail**: Tombol "Pay Online" dan "Manual Payment"
- **Payment Index**: Tampilan status gateway dan method
- **Payment Show**: Support untuk field Tripay

## Cara Penggunaan

### Setup Credentials Tripay
1. Daftar akun di [Tripay.co.id](https://tripay.co.id)
2. Dapatkan API Key, Private Key, dan Merchant Code dari dashboard
3. Update file `.env`:
```env
TRIPAY_ENVIRONMENT=sandbox
TRIPAY_SANDBOX_API_KEY=your_api_key
TRIPAY_SANDBOX_PRIVATE_KEY=your_private_key
TRIPAY_SANDBOX_MERCHANT_CODE=your_merchant_code
```

### Flow Pembayaran
1. **Customer mengakses invoice** → Klik "Pay Online"
2. **Pilih metode pembayaran** → Input data customer → Submit
3. **Redirect ke Tripay** → Customer melakukan pembayaran
4. **Callback/Webhook** → Tripay kirim notifikasi ke sistem
5. **Update status** → Invoice otomatis ter-update jika pembayaran berhasil

### Webhook Configuration
- **Callback URL**: `https://yourdomain.com/api/tripay/callback`
- **Return URL**: `https://yourdomain.com/billing/payments/success`
- Set di dashboard Tripay atau via API

## Keamanan

### 1. Signature Verification
- Semua request dari Tripay diverifikasi menggunakan HMAC SHA256
- Private key digunakan untuk generate dan verify signature
- Mencegah callback palsu

### 2. Status Validation
- Double-check status dengan API Tripay
- Status hanya di-update jika signature valid
- Log semua activity untuk audit trail

### 3. Error Handling
- Comprehensive error handling di semua method
- Logging error untuk debugging
- Graceful fallback untuk kondisi error

## Testing

### Sandbox Testing
1. Gunakan credentials sandbox dari Tripay
2. Set `TRIPAY_ENVIRONMENT=sandbox` di .env
3. Test dengan nominal kecil
4. Monitor logs untuk debugging

### Production Deployment
1. Ganti credentials ke production
2. Set `TRIPAY_ENVIRONMENT=production`
3. Update callback URL di dashboard Tripay
4. Test dengan nominal kecil sebelum go-live

## Monitoring & Maintenance

### Log Files
- Laravel logs: `storage/logs/laravel.log`
- Monitor callback errors
- Track payment status changes

### Database Monitoring
- Monitor tabel `payments` untuk status gateway
- Check field `gateway_response` untuk debug
- Monitor `expired_at` untuk pembayaran expired

### Performance
- Index pada field `merchant_ref`, `tripay_reference`, `gateway_status`
- Cleanup expired payments secara periodik
- Monitor API response time Tripay

## Troubleshooting

### Common Issues
1. **Invalid Signature**: Check private key dan format data
2. **Callback tidak diterima**: Check URL dan firewall
3. **Payment stuck di pending**: Manual check status via API
4. **API Error**: Check credentials dan network connectivity

### Debug Mode
- Enable Laravel debug mode untuk development
- Check `gateway_response` field untuk detail error
- Monitor network requests di browser developer tools

## Support Payment Methods
Tripay mendukung berbagai metode pembayaran:
- Virtual Account (BCA, BNI, BRI, Mandiri, dll)
- E-Wallet (OVO, DANA, ShopeePay, LinkAja)
- Retail (Alfamart, Indomaret)
- Credit Card / Debit Card
- QRIS

## Kesimpulan
✅ **Integrasi Tripay berhasil diimplementasikan dengan lengkap**

Sistem billing internet sekarang mendukung:
- Pembayaran online via berbagai metode
- Otomatis update status invoice
- Webhook handling yang aman
- UI yang user-friendly
- Monitoring dan logging yang comprehensive

Sistem siap untuk production dengan konfigurasi credentials yang tepat.