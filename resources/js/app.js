const money = new Intl.NumberFormat('en-MY', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

document.querySelectorAll('[data-service-note-form]').forEach((form) => {
    const serviceCharge = form.querySelector('[data-service-charge]');
    const partsCharge = form.querySelector('[data-parts-charge]');
    const totalCharge = form.querySelector('[data-total-charge]');
    const deviceType = form.querySelector('[data-device-type]');
    const deviceTypeOtherWrap = form.querySelector('[data-device-type-other-wrap]');
    const deviceTypeOther = form.querySelector('[name="device_type_other"]');

    const numericValue = (input) => Number.parseFloat(input?.value || '0') || 0;

    const updateTotal = () => {
        totalCharge.value = money.format(numericValue(serviceCharge) + numericValue(partsCharge));
    };

    const updateDeviceTypeOther = () => {
        const isOther = deviceType.value === 'Others';

        deviceTypeOtherWrap.classList.toggle('hidden', !isOther);
        deviceTypeOther.required = isOther;

        if (!isOther) {
            deviceTypeOther.value = '';
        }
    };

    serviceCharge.addEventListener('input', updateTotal);
    partsCharge.addEventListener('input', updateTotal);
    deviceType.addEventListener('change', updateDeviceTypeOther);
    form.addEventListener('reset', () => {
        window.setTimeout(() => {
            updateTotal();
            updateDeviceTypeOther();
        });
    });

    updateTotal();
    updateDeviceTypeOther();
});
