//const { json } = require('stream/consumers')
const sortByValue = require('./mapFunctions')

test('Sorts a json with 5 values', () => {
    let happyPathJson1 = { '1234': 1.00, '3512': 0.432, '1111': 0.3255, '0002': 0.999999, '5563': 0.0001};
    let sortedJson1 = [[1, '1234'], [0.999999, '0002'], [0.432, '3512'], [0.3255, '1111'], [0.0001, '5563']];
    let result1 = sortByValue(happyPathJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 3 values', () => {
    let happyPathJson1 = { '143': 1.123, '1': -0.9986, '55': 21};
    let sortedJson1 = [[21, '55'], [1.123, '143'], [-0.9986, '1']];
    let result1 = sortByValue(happyPathJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 1 values', () => {
    let emptyJson1 = {'a': -1239241};
    let sortedJson1 = [[-1239241, 'a']];
    let result1 = sortByValue(emptyJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 0 values', () => {
    let emptyJson1 = {};
    let sortedJson1 = [];
    let result1 = sortByValue(emptyJson1);
    expect(result1).toStrictEqual(sortedJson1);
})