export const TicTacToe = {
    // Элемент с полями в дом дереве
    el: null,

    // Булевое значение закончилась ли игра
    isGameEnd: false,

    // Булевое значение
    // true если текущий ход у X
    // false если текущий ход у O
    isXTurn: true,

    // Матрица 3 на 3 с информацией о полях
    matrix: [
        [null, null, null],
        [null, null, null],
        [null, null, null],
    ],

    // массив победных комбинаций
    wonCombinations: [
        [[1, 1], [1, 2], [1, 3]],
        [[2, 1], [2, 2], [2, 3]],
        [[3, 1], [3, 2], [3, 3]],
        [[1, 1], [2, 2], [3, 3]],
        [[1, 3], [2, 2], [3, 1]],
        [[1, 1], [2, 1], [3, 1]],
        [[1, 2], [2, 2], [3, 2]],
        [[1, 3], [2, 3], [3, 3]],
    ],

    init({el, onMove}) {
        this.el = el
        this.onMove = onMove
        this.boxes = el.querySelectorAll('.tic-tac-toe__ceil')

        return this
    },

    initListeners() {
        this.boxes.forEach(box => {
            box.addEventListener('click', event => {
                if (this.isGameEnd || !this.isBlockEmpty(event.target)) {
                    return
                }

                this.setBlockValue(event.target)
                this.setBlockDom(event.target)

                if (this.checkForWin()) {
                    this.setGameEndStatus()
                }

                if (!this.checkHasEmptyBlocks()) {
                    this.setGameEndStatus()

                    if (!this.checkForWin()) {
                        setTimeout(() => {
                            alert('Конец игры (Ничья)')
                        })
                        return
                    }
                }

                if (this.isGameEnd) {
                    setTimeout(() => {
                        alert('Победил ' + this.getCurrentTurnValue())
                    })
                } else {
                    this.changeTurnValue()
                    if (this.onMove) {
                        this.onMove(this.isXTurn)
                    }
                }
            })
        })
    },

    /**
     * Проверка на наличие пустых блоков
     * @returns {boolean} - true если есть пустые блоки, false - если нет
     */
    checkHasEmptyBlocks() {
        return this.matrix.some(row => row.some(cell => cell === null));
    },

    startGame() {
        this.initListeners()
        this.onMove(this.isXTurn)
    },

    /**
     * Сброс данных и очищение дом дерева
     */
    restartGame() {
        this.isGameEnd = false;
        this.isXTurn = true;

        // Очищаем каждую ячейку в DOM и в матрице
        this.boxes.forEach(box => {
            this.setBlockValue(box, true);
            this.setBlockDom(box, true);
        });

        // Сбрасываем текст "Сейчас ходит"
        if (this.onMove) {
            this.onMove(this.isXTurn);
        }
    },

    isBlockEmpty(target) {
        const [row, col] = this.getBlockPosition(target)
        return !this.matrix[row - 1][col - 1]
    },

    getBlockPosition(target) {
        const {row, col} = target.dataset
        return [row, col]
    },

    /**
     * Изменение значения элемента в матрице
     */
    setBlockValue(target, clear) {
        const [row, col] = this.getBlockPosition(target);
        // Если clear true, ставим null, иначе текущий ход (X или O)
        const value = clear ? null : this.getCurrentTurnValue();
        this.matrix[row - 1][col - 1] = value;
    },

    /**
     * Изменение значения элемента в дом дереве
     */
    setBlockDom(target, clear) {
        // Если clear true, ставим пустую строку, иначе текущий ход
        target.innerText = clear ? '' : this.getCurrentTurnValue();
    },

    /**
     * Получение строки с текущем ходом
     */
    getCurrentTurnValue() {
        return this.isXTurn ? 'X' : 'O';
    },

    /**
     * Изменение текущего хода в данных
     */
    changeTurnValue() {
        this.isXTurn = !this.isXTurn;
    },

    checkForWin() {
        for (let i = 0; i < this.wonCombinations.length; i++) {
            const [first, second, third] = this.wonCombinations[i]

            if (
                this.matrix[first[0] - 1][first[1] - 1] &&
                this.matrix[first[0] - 1][first[1] - 1] === this.matrix[second[0] - 1][second[1] - 1] &&
                this.matrix[third[0] - 1][third[1] - 1] === this.matrix[second[0] - 1][second[1] - 1]
            ) {
                return true
            }
        }
        return false
    },

    /**
     * Установить статус об окончании игры
     */
    setGameEndStatus() {
        this.isGameEnd = true;
    }
}