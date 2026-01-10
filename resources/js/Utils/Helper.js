import _ from 'lodash';
import CryptoJS from 'crypto-js';
import moment from 'moment';
import { AES_SECRET,AES_IV } from '../Config/Index';

//get storage data
export const getStorageData = (key) => {
    const data = _.isEmpty(localStorage.getItem(key))
            ? {}
            : localStorage.getItem(key);
    // Decrypt
    if (data.length) {
        let secret = CryptoJS.enc.Utf8.parse(AES_SECRET);
        let iv = CryptoJS.enc.Utf8.parse(AES_IV);
        const bytes = CryptoJS.AES.decrypt(data, secret, { iv });
        const decryptedData = JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
        return _.isEmpty(decryptedData) ? {} : decryptedData;
    } else {
        return false;
    }
}
//set Storage Data
export const setStorageData = (key,value) => {
    value = JSON.stringify(value)
    let secret = CryptoJS.enc.Utf8.parse(AES_SECRET);
    let iv = CryptoJS.enc.Utf8.parse(AES_IV);
    const ciphertext = CryptoJS.AES.encrypt(value, secret, {
        iv,
    }).toString();
    localStorage.setItem(key, ciphertext);
}
//remove local storage
export const removeStorageData = () => {
    localStorage.clear();
    return true;
}
//get date and time
export const dateTimeFormat = (datetime) => {
    return moment(datetime).format("YYYY-MM-DD hh:mm:ss");
}
// get date
export const dateFormat = (date) => {
    return moment(date).format("YYYY-MM-DD");
}
//encrypt data
export const encryptCryptoString = (data) => {
    let key = CryptoJS.enc.Utf8.parse(AES_SECRET);
    let iv  = CryptoJS.enc.Utf8.parse(AES_IV);
    const ciphertext = CryptoJS.AES.encrypt(data, key, { iv }).toString();
    return ciphertext;
}
