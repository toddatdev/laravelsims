var myNode = false;
function getResourceById(id) {
    let ret = {};
    for(let i = 0; i < resources.length; ++i) {
        if(resources[i].id == id) {
            ret = resources[i];
            return ret;
        }
    }
    return ret;
}
function getRndInteger() {
    let min = 1;
    let max = 9999;
    return Math.floor(Math.random() * (max - min + 1) ) + min;
}
function getUid() {
        let now = new Date();
        return now.getTime()+"-"+Math.random() * (100 - 1) + 1;
}
function byAbbrv(a,b) {
    if (a.abbrv < b.abbrv)
        return -1;
    if (a.abbrv > b.abbrv)
        return 1;
    return 0;
}
