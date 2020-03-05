let vm = new Vue({
    el: '#vue-instance',
    data: {
        inventory: []
    }
});

function foo(){
    let image = 'https://i.pinimg.com/originals/2a/df/fb/2adffbee6e939b2bd1e32ffa8c763308.jpg';
    let inv = [{name: 'MacBook Air PRO', price: 1000,
        image: image},
        {name: 'MacBook Pro', price: 1800, image: image},
        {name: 'Lenovo W530', price: 1400, image: image},
        {name: 'Acer Aspire One', price: 300, image: image}];
    vm.inventory = inv;
}