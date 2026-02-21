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

    constructor(type, size) {
        if (!Pizza.TYPES[type]) {
            throw new Error(`Неизвестный вид пиццы: "${type}". Доступные: ${Object.keys(Pizza.TYPES).join(', ')}`);
        }
        if (!Pizza.SIZES[size]) {
            throw new Error(`Неизвестный размер: "${size}". Доступные: ${Object.keys(Pizza.SIZES).join(', ')}`);
        }

        this.type = type;
        this.size = size;
        this.toppings = [];
    }

    addTopping(topping) {
        if (!Pizza.TOPPINGS[topping]) {
            throw new Error(`Неизвестная добавка: "${topping}". Доступные: ${Object.keys(Pizza.TOPPINGS).join(', ')}`);
        }
        if (this.toppings.includes(topping)) {
            console.log(`Добавка "${topping}" уже добавлена.`);
            return;
        }
        this.toppings.push(topping);
        console.log(`Добавка "${topping}" добавлена.`);
    }

    removeTopping(topping) {
        const index = this.toppings.indexOf(topping);
        if (index === -1) {
            console.log(`Добавка "${topping}" не найдена в пицце.`);
            return;
        }
        this.toppings.splice(index, 1);
        console.log(`Добавка "${topping}" убрана.`);
    }

    getToppings() {
        return [...this.toppings];
    }

    getSize() {
        return this.size;
    }

    getStuffing() {
        return this.type;
    }

    calculatePrice() {
        let price = Pizza.TYPES[this.type].price;
        price += Pizza.SIZES[this.size].price;

        for (const topping of this.toppings) {
            price += Pizza.TOPPINGS[topping][this.size].price;
        }

        return price;
    }

    calculateCalories() {
        let calories = Pizza.TYPES[this.type].calories;
        calories += Pizza.SIZES[this.size].calories;

        for (const topping of this.toppings) {
            calories += Pizza.TOPPINGS[topping][this.size].calories;
        }

        return calories;
    }

    getInfo() {
        const toppingsList = this.toppings.length > 0
            ? this.toppings.join(', ')
            : 'нет';

        return `
\n
  Пицца: ${this.type}
  Размер: ${this.size}
  Добавки: ${toppingsList}

  Цена: ${this.calculatePrice()} руб.
  Калорийность: ${this.calculateCalories()} Ккал
\n`;
    }
}

// Тест : большая маргарита — добавить и убрать добавку
console.log('Пицца 1');
const pizza3 = new Pizza('Маргарита', 'Большая');
pizza3.addTopping('сливочная моцарелла');
pizza3.addTopping('сырный борт');
console.log(`Добавки до удаления: ${pizza3.getToppings()}`);

pizza3.removeTopping('сырный борт');
console.log(`Добавки после удаления: ${pizza3.getToppings()}`);

console.log(pizza3.getInfo());
// Цена: 500 + 200 + 50 = 750 руб.
// Калории: 300 + 200 + 20 = 520 Ккал