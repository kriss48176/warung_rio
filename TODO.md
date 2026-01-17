# TODO: Fix Order Confirmation and Admin Display Issues

## Issues Identified:
- COD confirm button not clickable due to JavaScript logic
- Inconsistent bukti_pembayaran storage (full path vs filename)
- Transaksi model pelanggan relation uses wrong field name
- Admin transaksi view inconsistent image paths

## Steps to Fix:

### 1. Fix JavaScript in checkout.blade.php for COD confirm button
- Add initial checkFormValidity() call on DOMContentLoaded to enable COD button by default

### 2. Standardize bukti_pembayaran storage in CheckoutController
- Change store() method to save only filename instead of full path
- Update uploadBukti() method to save only filename

### 3. Fix Transaksi model pelanggan relation
- Change relation to use 'name' field instead of 'nama'

### 4. Update admin transaksi views for consistent image paths
- Change index.blade.php to use asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran)
- Ensure all views use consistent path format

### 5. Test the fixes
- Verify COD confirmation works
- Verify bank transfer with bukti_pembayaran works
- Verify orders appear in admin
- Verify bukti_pembayaran images display correctly
