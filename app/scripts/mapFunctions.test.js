//const { json } = require('stream/consumers')
const scriptFunction = require('./mapFunctions')

//
// sortByValue Tests (US 14)
//
test('Sorts a json with 5 values', () => {
    let happyPathJson1 = { '1234': 1.00, '3512': 0.432, '1111': 0.3255, '0002': 0.999999, '5563': 0.0001};
    let sortedJson1 = [[1, '1234'], [0.999999, '0002'], [0.432, '3512'], [0.3255, '1111'], [0.0001, '5563']];
    let result1 = scriptFunction.sortByValue(happyPathJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 3 values', () => {
    let happyPathJson1 = { '143': 1.123, '1': -0.9986, '55': 21};
    let sortedJson1 = [[21, '55'], [1.123, '143'], [-0.9986, '1']];
    let result1 = scriptFunction.sortByValue(happyPathJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 1 values', () => {
    let emptyJson1 = {'a': -1239241};
    let sortedJson1 = [[-1239241, 'a']];
    let result1 = scriptFunction.sortByValue(emptyJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

test('Sorts a json with 0 values', () => {
    let emptyJson1 = {};
    let sortedJson1 = [];
    let result1 = scriptFunction.sortByValue(emptyJson1);
    expect(result1).toStrictEqual(sortedJson1);
})

//
// changeColor Tests (US 20)
//
test('Changes the color of the marker among 3 pois', () => {
    let savedPois = [    
        ['123', 'some data'],
        ['456', 'some other data'],
        ['789', 'even more data'],
    ];
    let marker = {
        _icon: {
            style: {},
        },
    };
    let apiId = '456';
  
    let result = scriptFunction.changeColor(savedPois, marker, apiId);
    expect(marker._icon.style.filter).toBe('hue-rotate(120deg)');
    expect(result).toBe(true);
});

test('Doesnt Change the color of the marker among 3 pois', () => {
    let savedPois = [    
        ['123', 'some data'],
        ['456', 'some other data'],
        ['789', 'even more data'],
    ];
    let marker = {
        _icon: {
            style: {},
        },
    };
    let apiId = '457';

    let result = scriptFunction.changeColor(savedPois, marker, apiId);
    expect(marker._icon.style).toStrictEqual({});
    expect(result).toBe(false);
});

test('Doesnt Change the color of the markers among 0 pois', () => {
    let savedPois = [];
    let marker = {
        _icon: {
            style: {},
        },
    };
    let apiId = '457';

    let result = scriptFunction.changeColor(savedPois, marker, apiId);
    expect(marker._icon.style).toStrictEqual({});
    expect(result).toBe(false);
});

test('Changes the color of the marker among 1 poi', () => {
    let savedPois = [    
        ['123', 'some data'],
    ];
    let marker = {
        _icon: {
            style: {},
        },
    };
    let apiId = '123';

    let result = scriptFunction.changeColor(savedPois, marker, apiId);
    expect(marker._icon.style.filter).toBe('hue-rotate(120deg)');
    expect(result).toBe(true);
});

//
// getNewCoordinate Tests (US 21)
//
test('Finds the coordinate when there is 3 saved poi', () => {
    let savedPois = [    
        ['123', '00:00', 'data 2', 'data 3', 'data 4', 39.53452, -86.36474],
        ['456', '05:00', 'data 2.2', 'data 3.2', 'data 4', 39.234245, -86.123673],
        ['789', '03:00', 'data 2.3', 'data 3.3', 'data 4', 39.12343, -86.4576],
    ];
    let apiId = '123';
    let cord = scriptFunction.getNewCoordinate(savedPois, apiId);
    expect(cord).toStrictEqual([39.53452, -86.36474, '00:00'])
});

test('doesnt find the coordinate when there is 3 saved poi', () => {
    let savedPois = [    
        ['123', '00:00', 'data 2', 'data 3', 'data 4', 39.53452, -86.36474],
        ['456', '05:00', 'data 2.2', 'data 3.2', 'data 4', 39.234245, -86.123673],
        ['789', '03:00', 'data 2.3', 'data 3.3', 'data 4', 39.12343, -86.4576],
    ];
    let apiId = '124';
    let cord = scriptFunction.getNewCoordinate(savedPois, apiId);
    expect(cord).toStrictEqual([])
});

test('Finds the coordinate when there is 1 saved poi', () => {
    let savedPois = [    
        ['456', '05:00', 'data 2.2', 'data 3.2', 'data 4', 39.234245, -86.123673],
    ];
    let apiId = '456';
    let cord = scriptFunction.getNewCoordinate(savedPois, apiId);
    expect(cord).toStrictEqual([39.234245, -86.123673, '05:00'])
});

test('doesnt find the coordinate when there is 0 saved pois', () => {
    let savedPois = [];
    let apiId = '456';
    let cord = scriptFunction.getNewCoordinate(savedPois, apiId);
    expect(cord).toStrictEqual([])
});

//
// checkPoiSaved (US 19)
//
test('check that the poi is saved according to 3 apiIds', () => {
    let itineraryApiArray = ['123', '456', '789'];
    let apiId = '789';
    let saved = scriptFunction.checkPoiSaved(itineraryApiArray, apiId)
    expect(saved).toBe(true)
});

test('check that the poi is not saved according to 3 apiIds', () => {
    let itineraryApiArray = ['123', '456', '789'];
    let apiId = '769';
    let saved = scriptFunction.checkPoiSaved(itineraryApiArray, apiId)
    expect(saved).toBe(false)
});

test('check that poi is not found when itnineraryArray has no elements', () => {
    let itineraryApiArray = [];
    let apiId = '123';
    let saved = scriptFunction.checkPoiSaved(itineraryApiArray, apiId)
    expect(saved).toBe(false)
});

test('check that poi is found when itnineraryArray has 1 elements', () => {
    let itineraryApiArray = ['123'];
    let apiId = '123';
    let saved = scriptFunction.checkPoiSaved(itineraryApiArray, apiId)
    expect(saved).toBe(true)
});
  
  