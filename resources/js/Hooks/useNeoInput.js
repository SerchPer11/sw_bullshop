import { computed, ref, useId, watch } from 'vue';

export function useNeoInput(props, emit) {
	const generatedId = useId();
	const showPassword = ref(false);
	const hasLocalError = ref(false);
	const localErrorMessage = ref('');
	const hasInvalidPhoneCharacters = ref(false);
	const isExternalErrorDismissed = ref(false);

	const fieldId = computed(() => props.id || generatedId);

	const validationConfig = computed(() => {
		if (typeof props.validation === 'object' && props.validation !== null) {
			return props.validation;
		}

		const configuredRule = props.validation || props.rule;

		return configuredRule ? { pattern: configuredRule } : {};
	});

	const errorMessage = computed(() => {
		if (!isExternalErrorDismissed.value) {
			if (typeof props.error === 'string') return props.error;
			if (Array.isArray(props.error)) return props.error[0] || '';
			if (props.error && typeof props.error === 'object') {
				return Object.values(props.error).flat(Infinity).find(value => typeof value === 'string') || '';
			}
		}

		return localErrorMessage.value;
	});

	const visibleError = computed(() => Boolean(errorMessage.value) || hasLocalError.value);

	const emitValidity = (valid) => {
		emit('validity-change', valid);
	};

	watch(() => props.error, (error) => {
		isExternalErrorDismissed.value = false;

		if (error) {
			hasLocalError.value = false;
			localErrorMessage.value = '';
			emitValidity(false);
		}
	});

	const valueAsString = (value) => value === null || value === undefined ? '' : String(value);

	const isEmpty = (value) => {
		if (Array.isArray(value)) return value.length === 0;
		if (typeof value === 'boolean') return value === false;
		return value === null || value === undefined || valueAsString(value).trim() === '';
	};

	const formatMxPhone = (value) => {
		const digits = valueAsString(value).replace(/\D/g, '').slice(0, 10);

		if (digits.length <= 3) return digits ? `(${digits}` : '';
		if (digits.length <= 6) return `(${digits.slice(0, 3)}) ${digits.slice(3)}`;

		return `(${digits.slice(0, 3)}) ${digits.slice(3, 6)}-${digits.slice(6)}`;
	};

	const isPhone = computed(() => props.formatter === 'mx-phone' || props.type === 'tel');

	const normalizeValue = (value) => {
		if (isPhone.value) {
			return valueAsString(value).replace(/\D/g, '').slice(0, 10);
		}

		return value;
	};

	const displayValue = ref(isPhone.value ? formatMxPhone(props.modelValue) : props.modelValue);

	watch(() => props.modelValue, (value) => {
		displayValue.value = isPhone.value ? formatMxPhone(value) : value;
	});

	const failValidation = (message) => {
		hasLocalError.value = true;
		localErrorMessage.value = message;
	};

	const validate = () => {
		const value = normalizeValue(props.modelValue);
		const stringValue = valueAsString(value);
		const config = validationConfig.value;

		hasLocalError.value = false;
		localErrorMessage.value = '';

		if (isPhone.value && hasInvalidPhoneCharacters.value) {
			failValidation('Escribe un teléfono mexicano de 10 dígitos.');
			emitValidity(false);
			return false;
		}

		if ((props.required || config.required) && isEmpty(value)) {
			failValidation('Este campo es obligatorio.');
			emitValidity(false);
			return false;
		}

		if (isEmpty(value)) {
			emitValidity(true);
			return true;
		}

		const pattern = config.pattern || (props.type === 'email' ? 'email' : props.type === 'tel' ? 'phone-mx' : '');

		if (config.minLength && stringValue.length < config.minLength) {
			failValidation(`Debe tener al menos ${config.minLength} caracteres.`);
		} else if (config.maxLength && stringValue.length > config.maxLength) {
			failValidation(`No debe superar ${config.maxLength} caracteres.`);
		} else if (pattern === 'email' && !/^\S+@\S+\.\S+$/.test(stringValue)) {
			failValidation('Escribe un correo electrónico válido.');
		} else if (pattern === 'phone-mx' && !/^\d{10}$/.test(stringValue)) {
			failValidation('Escribe un teléfono mexicano de 10 dígitos.');
		} else if (pattern === 'numeric' && !/^\d+$/.test(stringValue)) {
			failValidation('Solo se permiten números.');
		} else if (pattern === 'alpha' && !/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(stringValue)) {
			failValidation('Solo se permiten letras y espacios.');
		} else if (pattern === 'alphanumeric' && !/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/.test(stringValue)) {
			failValidation('Solo se permiten letras, números y espacios.');
		} else if (config.pattern instanceof RegExp && !config.pattern.test(stringValue)) {
			failValidation('El formato no es válido.');
		}

		const valid = !hasLocalError.value;
		emitValidity(valid);

		return valid;
	};

	const dismissError = () => {
		isExternalErrorDismissed.value = true;
		hasLocalError.value = false;
		localErrorMessage.value = '';
	};

	const normalizedOptions = computed(() => {
		if (Array.isArray(props.options)) return props.options;

		if (typeof props.options === 'string') {
			try {
				const parsedOptions = JSON.parse(props.options);
				return Array.isArray(parsedOptions) ? parsedOptions : [];
			} catch {
				return [];
			}
		}

		return [];
	});

	const computedInputType = computed(() => {
		if (props.type === 'password') return showPassword.value ? 'text' : 'password';
		return props.type;
	});

	const computedInputAttrs = computed(() => ({
		...props.inputAttrs,
		inputmode: props.inputAttrs.inputmode || (props.type === 'tel' ? 'numeric' : undefined),
		autocomplete: props.inputAttrs.autocomplete || (props.type === 'email' ? 'email' : props.type === 'tel' ? 'tel' : undefined),
		maxlength: props.inputAttrs.maxlength || (isPhone.value ? 14 : undefined),
	}));

	const optionValue = (option) => option?.id ?? option;
	const optionLabel = (option) => option?.name ?? option?.label ?? option;
	const optionKey = (option) => String(optionValue(option));

	const isCheckboxChecked = (option) => {
		const value = optionValue(option);
		return Array.isArray(props.modelValue)
			? props.modelValue.includes(value)
			: props.modelValue === true;
	};

	const toggleCheckbox = (option, checked) => {
		dismissError();
		const value = optionValue(option);

		if (!Array.isArray(props.modelValue)) {
			emit('update:modelValue', checked);
			return;
		}

		const values = props.modelValue.filter(selectedValue => selectedValue !== value);
		if (checked && props.maxSelections && props.modelValue.length >= props.maxSelections) {
			failValidation(`Puedes seleccionar hasta ${props.maxSelections} opciones.`);
			emitValidity(false);
			return;
		}
		if (checked) values.push(value);
		emit('update:modelValue', values);
		emitValidity(true);
	};

	const handleInput = (inputValue) => {
		dismissError();
		const rawValue = valueAsString(inputValue);

		hasInvalidPhoneCharacters.value = isPhone.value && /[^\d\s()+-]/.test(rawValue);
		const normalizedValue = normalizeValue(inputValue);

		displayValue.value = isPhone.value ? formatMxPhone(inputValue) : inputValue;

		emit('update:modelValue', normalizedValue);
		if (!hasInvalidPhoneCharacters.value) emitValidity(true);
	};

	const handleSwitchUpdate = (value) => {
		dismissError();
		emit('update:modelValue', value);
		emitValidity(true);
	};

	const handleSelectUpdate = (value) => {
		dismissError();
		const selectedOption = normalizedOptions.value.find(option => String(optionValue(option)) === String(value));
		emit('update:modelValue', selectedOption === undefined ? value : optionValue(selectedOption));
		emitValidity(true);
	};

	return {
		computedInputAttrs,
		computedInputType,
		displayValue,
		dismissError,
		errorMessage,
		fieldId,
		handleInput,
		handleSelectUpdate,
		handleSwitchUpdate,
		isCheckboxChecked,
		normalizedOptions,
		optionValue,
		optionKey,
		optionLabel,
		showPassword,
		toggleCheckbox,
		validate,
		visibleError,
	};
}
