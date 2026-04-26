class Pizza {
    static TYPES = {
        'Маргарита': { price: 500, calories: 300 },
        'Пепперони': { price: 800, calories: 400 },
        'Баварская': { price: 700, calories: 450 },
    };

    static SIZES = {
        'Большая': { price: 200, calories: 200 },
        'Маленькая': { price: 100, calories: 100 },
    };

    static TOPPINGS = {
        'сливочная моцарелла': {
            'Маленькая': { price: 99, calories: 25 },
            'Большая':   { price: 129, calories: 35 },
        },
        'сырный борт': {
            'Маленькая': { price: 189, calories: 70 },
            'Большая':   { price: 289, calories: 100 },
        },
        'чеддер и пармезан': {
            'Маленькая': { price: 99, calories: 40 },
            'Большая':   { price: 139, calories: 60 },
        },
    };

    constructor() {
        this.type = null;
        this.size = null;
        this.toppings = [];
    }

    setType(type) {
        if (!Pizza.TYPES[type]) {
            throw new Error(`Неизвестный вид пиццы: "${type}"`);
        }
        this.type = type;
    }

    setSize(size) {
        if (!Pizza.SIZES[size]) {
            throw new Error(`Неизвестный размер: "${size}"`);
        }
        this.size = size;
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) {
            throw new Error(`Неизвестная добавка: "${topping}"`);
        }
        if (!this.toppings.includes(topping)) {
            this.toppings.push(topping);
        }
    }

    removeTopping(topping) {
        const index = this.toppings.indexOf(topping);
        if (index !== -1) {
            this.toppings.splice(index, 1);
        }
    }

    hasTopping(topping) {
        return this.toppings.includes(topping);
    }

    isComplete() {
        return this.type !== null && this.size !== null;
    }

    calculatePrice() {
        if (!this.isComplete()) return 0;

        let price = Pizza.TYPES[this.type].price + Pizza.SIZES[this.size].price;

        for (const topping of this.toppings) {
            price += Pizza.TOPPINGS[topping][this.size].price;
        }
        return price;
    }

    calculateCalories() {
        if (!this.isComplete()) return 0;

        let calories = Pizza.TYPES[this.type].calories + Pizza.SIZES[this.size].calories;

        for (const topping of this.toppings) {
            calories += Pizza.TOPPINGS[topping][this.size].calories;
        }
        return calories;
    }
}

// ==================== ИНИЦИАЛИЗАЦИЯ ====================
let pizza = new Pizza();

// ==================== ВЫБОР ПИЦЦЫ ====================
document.querySelectorAll('.pizza-form').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.pizza-form').forEach(p => p.classList.remove('selected'));
        item.classList.add('selected');

        pizza.setType(item.dataset.type);
        updateButton();
    });
});

// ==================== ВЫБОР РАЗМЕРА (radio) ====================
document.querySelectorAll('input[name="size"]').forEach(radio => {
    radio.addEventListener('change', () => {
        if (radio.checked) {
            pizza.setSize(radio.value);
            updateToppingPrices();
            updateButton();
        }
    });
});

// ==================== ВЫБОР ДОБАВОК ====================
document.querySelectorAll('.order-pizza-toppings-form').forEach(item => {
    item.addEventListener('click', () => {
        const topping = item.dataset.topping;

        if (pizza.hasTopping(topping)) {
            pizza.removeTopping(topping);
            item.classList.remove('selected');
        } else {
            pizza.addTopping(topping);
            item.classList.add('selected');
        }

        updateButton();
    });
});

// ==================== ОБНОВЛЕНИЕ ЦЕН ДОБАВОК ИЗ JS ====================
function updateToppingPrices() {
    if (!pizza.size) return;

    document.querySelectorAll('.order-pizza-toppings-form').forEach(card => {
        const toppingName = card.dataset.topping;
        const priceElement = card.querySelector('.addon-price');

        if (Pizza.TOPPINGS[toppingName] && priceElement) {
            const price = Pizza.TOPPINGS[toppingName][pizza.size].price;
            priceElement.textContent = price + ' ₽';
        }
    });
}

// ==================== ОБНОВЛЕНИЕ КНОПКИ ====================
function updateButton() {
    const totalPrice = pizza.calculatePrice();
    const totalCalories = pizza.calculateCalories();

    document.getElementById('price').textContent = totalPrice;
    document.getElementById('calories').textContent = totalCalories;
}

// ==================== КНОПКА "ДОБАВИТЬ В ЗАКАЗ" ====================
document.getElementById('calculateBtn').addEventListener('click', () => {
    if (!pizza.isComplete()) {
        alert('Пожалуйста, выберите пиццу и размер!');
        return;
    }

    const toppingsText = pizza.toppings.length > 0
        ? pizza.toppings.join(', ')
        : 'без добавок';

    const result = `Заказ: ${pizza.type} (${pizza.size}), ${toppingsText}.\n` +
        `Цена: ${pizza.calculatePrice()} руб.\n` +
        `Калории: ${pizza.calculateCalories()} кКал`;

    document.getElementById('result').textContent = result;
});

// ==================== ЗАПУСК ПРИ ЗАГРУЗКЕ ====================
function init() {
    // Устанавливаем размер по умолчанию из выбранного radio
    const checkedSize = document.querySelector('input[name="size"]:checked');
    if (checkedSize) {
        pizza.setSize(checkedSize.value);
    }

    updateToppingPrices();
    updateButton();
}

init();