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

document.querySelectorAll('[data-signature-pad]').forEach((pad) => {
    const canvas = pad.querySelector('[data-signature-canvas]');
    const input = pad.querySelector('[data-signature-input]');
    const clearButton = pad.querySelector('[data-signature-clear]');
    const status = pad.querySelector('[data-signature-status]');

    if (!(canvas instanceof HTMLCanvasElement) || !input) {
        return;
    }

    const context = canvas.getContext('2d');

    if (!context) {
        return;
    }

    let isDrawing = false;
    let hasSignature = false;
    let lastPoint = null;

    const setStatus = (message, isCaptured = false) => {
        if (!status) {
            return;
        }

        status.textContent = message;
        status.classList.toggle('text-emerald-700', isCaptured);
        status.classList.toggle('text-slate-500', !isCaptured);
    };

    const resizeCanvas = () => {
        const existingData = hasSignature ? input.value || canvas.toDataURL('image/png') : '';
        const rect = canvas.getBoundingClientRect();
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        canvas.width = Math.max(Math.floor(rect.width * ratio), 1);
        canvas.height = Math.max(Math.floor(rect.height * ratio), 1);
        context.setTransform(ratio, 0, 0, ratio, 0, 0);
        context.lineCap = 'round';
        context.lineJoin = 'round';
        context.lineWidth = 2.4;
        context.strokeStyle = '#0f172a';

        if (existingData) {
            const image = new Image();
            image.onload = () => {
                context.drawImage(image, 0, 0, rect.width, rect.height);
            };
            image.src = existingData;
            input.value = existingData;
        } else {
            input.value = '';
        }
    };

    const pointFromEvent = (event) => {
        const rect = canvas.getBoundingClientRect();

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top,
        };
    };

    const syncInput = () => {
        input.value = hasSignature ? canvas.toDataURL('image/png') : '';
    };

    const startDrawing = (event) => {
        event.preventDefault();
        isDrawing = true;
        lastPoint = pointFromEvent(event);
        canvas.setPointerCapture?.(event.pointerId);
    };

    const draw = (event) => {
        if (!isDrawing || !lastPoint) {
            return;
        }

        event.preventDefault();
        const nextPoint = pointFromEvent(event);

        context.beginPath();
        context.moveTo(lastPoint.x, lastPoint.y);
        context.lineTo(nextPoint.x, nextPoint.y);
        context.stroke();

        lastPoint = nextPoint;
        hasSignature = true;
        syncInput();
        setStatus('Signature captured.', true);
    };

    const stopDrawing = (event) => {
        if (!isDrawing) {
            return;
        }

        event.preventDefault();
        isDrawing = false;
        lastPoint = null;
        syncInput();
    };

    const clearSignature = () => {
        context.clearRect(0, 0, canvas.width, canvas.height);
        hasSignature = false;
        input.value = '';
        setStatus('Belum ada signature baru.');
    };

    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointercancel', stopDrawing);
    canvas.addEventListener('pointerleave', stopDrawing);
    clearButton?.addEventListener('click', clearSignature);
    canvas.closest('form')?.addEventListener('reset', () => {
        window.setTimeout(clearSignature);
    });

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
});
