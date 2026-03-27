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
            'Маленькая': { price: 50, calories: 20 },
            'Большая': { price: 50, calories: 20 },
        },
        'сырный борт': {
            'Маленькая': { price: 150, calories: 50 },
            'Большая': { price: 300, calories: 50 },
        },
        'чеддер и пармезан': {
            'Маленькая': { price: 150, calories: 50 },
            'Большая': { price: 300, calories: 50 },
        },
    };

    constructor(type = null, size = null) {
        this.type = type;
        this.size = size;
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

        let price = Pizza.TYPES[this.type].price;
        price += Pizza.SIZES[this.size].price;

        for (const topping of this.toppings) {
            price += Pizza.TOPPINGS[topping][this.size].price;
        }

        return price;
    }

    calculateCalories() {
        if (!this.isComplete()) return 0;

        let calories = Pizza.TYPES[this.type].calories;
        calories += Pizza.SIZES[this.size].calories;

        for (const topping of this.toppings) {
            calories += Pizza.TOPPINGS[topping][this.size].calories;
        }

        return calories;
    }
}

// ========== ИНИЦИАЛИЗАЦИЯ ==========

let pizza = new Pizza();

//                 ВЫБОР ПИЦЦЫ
document.querySelectorAll('.pizza-form').forEach(item => {
    item.addEventListener('click', () => {
        document.querySelectorAll('.pizza-form').forEach(p => p.classList.remove('selected'));
        item.classList.add('selected');

        const type = item.dataset.type;
        pizza.setType(type);

        updateButton();
    });
});

//                ВЫБОР РАЗМЕРА
document.querySelectorAll('.size-option').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.size-option').forEach(b => b.classList.remove('selected'));
        button.classList.add('selected');

        const size = button.dataset.size;
        pizza.setSize(size);

        updateButton();
    });
});

//                  ВЫБОР ДОБАВОК
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

//                  ОБНОВЛЕНИЕ КНОПКИ
function updateButton() {
    const totalPrice = pizza.calculatePrice();
    const totalCalories = pizza.calculateCalories();

    document.getElementById('price').textContent = totalPrice;
    document.getElementById('calories').textContent = totalCalories;
}

//                  КНОПКА ЗАКАЗА
document.getElementById('calculateBtn').addEventListener('click', () => {
    if (!pizza.isComplete()) {
        alert('Пожалуйста, выберите пиццу и размер!');
        return;
    }

    const toppings = pizza.toppings.length > 0
        ? pizza.toppings.join(', ')
        : 'без добавок';

    const result = `Заказ: ${pizza.type} (${pizza.size}), ${toppings}. 
                    Цена: ${pizza.calculatePrice()} руб. 
                    Калории: ${pizza.calculateCalories()} кКал`;

    document.getElementById('result').textContent = result;
});

updateButton();