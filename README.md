# joomla-3.9-calculator


A Joomla module for real-time currency conversion with dual commission display in USD and RUB.

## Features

- 🤑 Real-time currency conversion between USD, EUR, RUB, and INR
- 📊 Dual commission display in both USD and RUB
- 💹 Auto-refreshing exchange rates (every 30 seconds)
- 🧮 Precise calculations with 8 decimal precision for INR
- 🎨 Responsive design with unified styling
- ⚙️ Separate rate configuration files for conversion and commissions

## Requirements

- Joomla 3.x or 4.x
- PHP 7.2+
- Modern web browser with JavaScript support

## Installation

1. **Copy module files**:
   ```bash
   cp -r mod_currency_converter /path/to/joomla/modules/
   ```

2. **Create rate files**:
   ```bash
   mkdir -p /path/to/joomla/modules/mod_currency_converter/
   touch exchange_rates.json exchange_rates1.json
   ```

3. **Set file permissions**:
   ```bash
   chmod 755 /path/to/joomla/modules/mod_currency_converter/
   chmod 644 /path/to/joomla/modules/mod_currency_converter/*.json
   ```

4. **Install through Joomla Admin**:
   - Go to `Extensions → Manage → Install`
   - Discover the module and publish it

## Configuration

### Rate Files Structure

1. **exchange_rates.json** (Conversion rates relative to INR):
   ```json
   {
     "USD": 0.012,
     "EUR": 0.011,
     "RUB": 1.0,
     "INR": 1.0
   }
   ```

2. **exchange_rates1.json** (Commission rates relative to RUB):
   ```json
   {
     "USD": 75.0,
     "EUR": 80.0,
     "RUB": 1.0,
     "INR": 1.0
   }
   ```

### Rate Updates

Update rates using either method:
- **Manual**: Edit JSON files directly
- **Automatic**: Set up cron job/custom script to update from financial API
- **Recommended update frequency**: Every 30 minutes

## Commission Logic

The module calculates commissions using:
1. 0.3% of transaction value in USD
2. Minimum $10 USD commission
3. Real-time conversion to RUB using current rates

## Customization

### CSS Variables
```css
#currency-converter {
    width: 320px;              /* Container width */
    border: 1px solid #ccc;     /* Border styling */
    background: #f9f9f9;        /* Background color */
}

.converter-input input,
.converter-input select {
    font-size: 16px;            /* Text size */
    border: 1px solid #ccc;     /* Element borders */
}
```

### Supported Currencies
Add new currencies by:
1. Adding options to both `<select>` elements
2. Updating rate JSON files with new currency codes
3. Modifying commission calculation logic if needed

