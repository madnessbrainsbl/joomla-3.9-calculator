<?php defined('_JEXEC') or die; ?>
<style>
#currency-converter {
    width: 320px;
    font-family: sans-serif;
    border: 1px solid #ccc;
    padding: 10px;
    background: #f9f9f9;
    margin: 0 auto;
}
.converter-input {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}
.converter-input input {
    padding: 5px;
    font-size: 16px;
    border: 1px solid #ccc;
    width: 75%;
    box-sizing: border-box;
}
.converter-input select {
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ccc;
    width: 20%;
    box-sizing: border-box;
    white-space: nowrap;
}
h3.title {
    font-size: 14px;
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
    font-family: sans-serif;
}
.commission-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}
.commission-table td {
    padding: 5px;
    text-align: center;
    font-size: 16px;
}
.commission-label {
    font-weight: bold;
    text-align: right;
    padding-right: 10px;
}
.number-value {
    color: #29ceec;
    font-family: sans-serif;
}
</style>

<h3 class="title">Обмен курса</h3>
<div id="currency-converter">
    <div class="converter-input">
        <select id="currency-from">
            <option value="USD" title="United States Dollar">USD</option>
            <option value="EUR" title="Euro">EUR</option>
            <option value="RUB" title="Russian Ruble">RUB</option>
            <option value="INR" title="Indian Rupee">INR</option>
        </select>
        <input type="number" id="amount" class="number-value" value="1" min="0" step="0.01">
    </div>
    <div class="converter-input">
        <select id="currency-to">
            <option value="USD" title="United States Dollar">USD</option>
            <option value="EUR" title="Euro">EUR</option>
            <option value="RUB" title="Russian Ruble">RUB</option>
            <option value="INR" title="Indian Rupee" selected>INR</option>
        </select>
        <input type="text" id="converted" class="number-value" readonly>
    </div>
    <table class="commission-table">
        <tr>
            <td class="commission-label">Комиссия:</td>
            <td><span id="commission-rub" class="number-value">0.00</span> руб</td>
        </tr>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let rates = {};
    let commissionRates = {};

    const elements = {
        amount: document.getElementById('amount'),
        from: document.getElementById('currency-from'),
        to: document.getElementById('currency-to'),
        result: document.getElementById('converted'),
        commissionRub: document.getElementById('commission-rub')
    };

    function loadRates() {
        fetch('<?php echo JURI::root(); ?>modules/mod_currency_converter/exchange_rates.json?nc=' + Date.now())
            .then(response => response.json())
            .then(data => {
                rates = data;
                updateConversion();
            })
            .catch(error => console.error('Ошибка загрузки курсов:', error));
    }

    function loadCommissionRates() {
        fetch('<?php echo JURI::root(); ?>modules/mod_currency_converter/exchange_rates1.json?nc=' + Date.now())
            .then(response => response.json())
            .then(data => {
                commissionRates = data;
                updateConversion();
            })
            .catch(error => console.error('Ошибка загрузки курсов комиссии:', error));
    }

    function updateConversion() {
        if (!rates[elements.from.value] || !rates[elements.to.value]) return;

        const amount = parseFloat(elements.amount.value) || 0;
        const convertedValue = (amount * rates[elements.from.value]) / rates[elements.to.value];
        const decimals = elements.to.value === 'INR' ? 8 : 2;
        elements.result.value = convertedValue.toFixed(decimals);

        if (commissionRates[elements.from.value] && commissionRates['USD']) {
            const amountInRUB = amount * commissionRates[elements.from.value];
            const amountInUSD = amountInRUB / commissionRates['USD'];
            const percentCommissionUSD = amountInUSD * 0.003;
            const commissionUSD = Math.max(10, percentCommissionUSD);
            const commissionRUB = commissionUSD * commissionRates['USD'];
            elements.commissionRub.textContent = commissionRUB.toFixed(2);
        } else {
            elements.commissionRub.textContent = '0.00';
        }
    }

    elements.from.addEventListener('change', updateConversion);
    elements.to.addEventListener('change', updateConversion);
    elements.amount.addEventListener('input', updateConversion);

    loadRates();
    loadCommissionRates();
    setInterval(loadRates, 30);
    setInterval(loadCommissionRates, 30);
    updateConversion();
});
</script>