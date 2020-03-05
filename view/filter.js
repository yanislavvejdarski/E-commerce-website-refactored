

$(function(){
    var vm = new Vue({
        el: '#vue-instance',
        data: {
            products: [],
        }
    });

    let filter = document.querySelector('#filter');

    let productsContainer = $("#products-container");
    let vueInstance = $('<div id="vue-instance"></div>');


    $('input[name=checkbox]').change(function(){
        if($(this).is(':checked')) {
            let checked = [];
            $("input:checkbox:checked").each(function () {
                let foo = $(this).parent().parent().data('filter');
                console.log(foo);


                let found = false;
                let index = -1;
                for (let i = 0; i < checked.length; i++) {
                    if (checked[i].name === foo) {
                        found = true;
                        index = i;
                        break;
                    }
                }
                let currentValue = $(this).val();
                if (found) {
                    checked[index].checkedValues.push(currentValue);
                } else {

                    let boo = {
                        name: foo,
                        checkedValues: [currentValue]
                    };
                    checked.push(boo);
                }
            });
            console.log(checked);
            vueInstance.show();
            $.post('index.php?target=product&action=filterProducts', {checked})
                .then((filtered) => {
                    console.log(filtered)
                    console.log("The type is: " + typeof(filtered));
                    productsContainer.hide();


                    vm.products = JSON.parse(filtered);
                }).catch((err) => console.error(err));

        }
        else {
        productsContainer.show();

        }
    });

    filter.addEventListener('click', () => {

    })
})
