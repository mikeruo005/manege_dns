import moment from "moment"

export function isYesterday(timestamp) {
    let t = moment(timestamp).format('YYYY/MM/DD 00:00:00')
    let theDayBeforeYesterday = moment().subtract(1, 'days').format('YYYY/MM/DD 00:00:00')
    return moment(t).isSame(theDayBeforeYesterday)
}

export function isToday(timestamp) {
    let t = moment(timestamp).format('YYYY/MM/DD 00:00:00')
    let today = moment().format('YYYY/MM/DD 00:00:00')
    return moment(t).isSame(today)
}

export function isTomorrow(timestamp) {
    let t = moment(timestamp).format('YYYY/MM/DD 00:00:00')
    let tomorrow = moment().add(1, "days").format('YYYY/MM/DD 00:00:00')
    return moment(t).isSame(tomorrow)
}

export function isInProgress(startTime, endTime) {
    let now = moment()
    return now >= moment(startTime) && now <= moment(endTime)
}
