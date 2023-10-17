const formatDateTimeJp = $dateTime => {
    const dateObj =  new Date($dateTime);
    const year = dateObj.getFullYear();
    const month = dateObj.getMonth() + 1;
    const day = dateObj.getDate();
    const hours = dateObj.getHours();
    const minutes = dateObj.getMinutes();
    const seconds = dateObj.getSeconds();

    return year + '年' + month + '月' + day + '日' + hours + '時' + minutes + '分' + seconds + '秒';
}

export {formatDateTimeJp}