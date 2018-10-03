export class WeekObject {
    constructor(number, year) {
        this.number = number
        this.year = year
    }

    weekend() {
        var dto = new Date(this.year, 0, 1 + (parseInt(this.number) - 1) * 7)

        var start = new Date(dto.setDate(dto.getDate() + 4))
        var end = new Date(dto.setDate(dto.getDate() + 2))

        return {start, end}
    }

    isOld(comparison) {
        return this.weekend().end < comparison
    }
}