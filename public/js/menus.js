$(document).ready(function () {

    let $menus = $(".menu-icon")
    let $additional_product = $(".supr-icon");

    /**
     * Permetre de faire dérouler les elements dans la partie commande
     * @param obj
     */
    function toggleDetail(obj) {
        let $next = $(obj).parent().next()
        if ($next.is(":visible")) {
            $next.hide()
        } else {
            $next.show()
        }
    }

    /**
     * Suppression d'un element de la partie commande
     * @param obj
     */
    function removeMenu(obj) {
        $(obj).parent().next().remove()
        $(obj).parent().remove()
        let index = ($menus).index(obj)

        //re-indexing
        $menus = $(".menu-icon")
        removeMenuRequest(index).catch(console.error)
    }

    /**
     * @param id
     * @returns {Promise<unknown>}
     */
    function fetchMenu(id) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/api/menus/${id}`,
                type: 'GET',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    /**
     * Gestion de la modal
     * @param data
     * @param menu
     */
    function updateModal(menu, sandwich, ingredients, menuId) {
        let titleContainer = $("#menu-modal");
        titleContainer.text("Menu " + menu.name + ' ( ' + sandwich.name + ' )');
        // Checkboxes choix ingredients generation
        let checkbox_ingredients_ctn = $("#checkbox_ingredients_ctn");

        checkbox_ingredients_ctn.empty();
        $("#comment").val("");
        ingredients.forEach(function (ingredient) {
            let $div;
            if (ingredient.type == "pain") {
                $div = $("<div>", {
                    class: "custom-control custom-checkbox col-sm",
                    hidden: true
                });
            } else {
                $div = $("<div>", {
                    class: "custom-control custom-checkbox col-sm"
                });
            }
            let $label = $("<label>", {
                class: "custom-control-label",
                for: ingredient.name,
                text: ingredient.name
            });

            let $input = $("<input>", {
                type: "checkbox",
                class: "custom-control-input ingredients-select",
                id: ingredient.name,
                value: ingredient.id,
                checked: true
            });
            $div.append($input);
            $div.append($label);

            checkbox_ingredients_ctn.append($div);
        });

        // Bouton "commander" evenements
        let addBtn = $("#addToCommand");
        addBtn.off();
        addBtn.on("click", function () {
            // fields
            let ingredients = [];
            let drink = $(".drink-select").val();

            let sauce = $(".sauce-select").val();
            let othersIngredients = $(".ingredients-select");

            othersIngredients.each(function (index, value) {
                let $current = $(value)
                if($current.is(":checked")){
                  ingredients.push($current.val());
                }
            });
            ingredients.push(sauce);

            let accompagniement = $(".accompagnement-select").val();
            let sandwichId = menu.sandwich.id;
            let comment = $("#comment").val();
            let data = {
                "menuId": menuId,
                "products": [
                    drink,
                    accompagniement,
                    sandwichId,
                ],
                "ingredients": ingredients,
                "comment": comment
            };

            addToMenu(data).then(function (res) {
                $('#custom-menu-modal').modal('hide')
                addMenuToDOM(res)
            }).catch(console.error);
        })
    }

    /**
     * Ajoute un menu au shopbag
     * @param data
     * @returns {Promise<unknown>}
     */
    function addToMenu(data) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/shopbag/add`,
                type: 'POST',
                data: data,
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    /**
     * Suppresion d'un menu du shopBag
     * @param index
     * @returns {Promise<unknown>}
     */
    function removeMenuRequest(index) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/shopbag/remove/${index}`,
                type: 'DELETE',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    function ingredientsRequest() {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/api/ingredients`,
                type: 'GET',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    function productCategoryRequest() {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/api/product_categories`,
                type: 'GET',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    function getSandwichRequest(sandwichRequestPath) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net${sandwichRequestPath}`,
                type: 'GET',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    /**
     * Ajoute les menus commander a la partie commande
     * @param data
     */
    async function addMenuToDOM(data) {

        let productCategories = await productCategoryRequest().catch(console.error);
        let sandwichCategory = productCategories['hydra:member'].filter(function (category) {
            return category.name == "sandwich";
        });

        let $div = $("<div>", {
            class: "menu-ctn"
        })
        let $span = $("<span>", {
            class: "menu-name",
            text: `1x ${data.menu.formule.name} - ${data.menu.formule.price}`
        })
        let $icon = $("<i>", {
            class: "menu-icon fas fa-minus"
        })
        let $detail = $("<div>", {
            class: "sub-product-container"
        })

        $span.on("click", function (e) {
            e.preventDefault()
            toggleDetail(this)
        })
        $icon.on("click", function (e) {
            e.preventDefault()
            removeMenu(this)
        })

        $div.append($span, $icon)
        for (const product of data.menu.content) {
            if (product.category == sandwichCategory[0]["@id"]) {
                let sandwichIngredients = await getIngredientsList(product).then().catch(console.error);
                let detailIngredientStr = "";
                sandwichIngredients.forEach(function (ingredient, ind) {
                    if (ingredient.type != "pain") {
                        if (ind == 0) {
                            if (ind == sandwichIngredients.length - 1) {
                                detailIngredientStr += `( ${ingredient.name} )`;
                            } else {
                                detailIngredientStr += `( ${ingredient.name} - `;
                            }
                        } else if (ind == sandwichIngredients.length - 1) {
                            detailIngredientStr += ` ${ingredient.name} )`;
                        } else {
                            detailIngredientStr += ` ${ingredient.name} -`;
                        }
                    }
                });
                $detail.append($("<p>", {
                    text: `- ${product.name}`,
                    class: "sub-product sandwich-name",
                    "data-toggle": "tooltip",
                    "data-placement": "top",
                    title: detailIngredientStr
                }));
            } else {
                $detail.append($("<p>", {
                    text: `- ${product.name}`,
                    class: "sub-product"
                }));
            }
        }

        if ($(".sub-product-container").length == 0) {
            $(".menu-list").after($div)
        } else {
            $(".sub-product-container").last().after($div)
        }
        $div.after($detail)

    }

    $(".menu-element").each(function () {
        $(this).on("click", async function () {
            let menuId = $(this).attr("data-menu-id");
            let menu = await fetchMenu(menuId).catch(console.error);

            //let sandwich = await getSandwichRequest(menu.sandwich).catch(console.error);
            //let ingredient = await getIngredientsList(sandwich).then().catch(console.error);

            updateModal(menu, menu.sandwich, menu.sandwich.ingredients, menuId);

            $('#custom-menu-modal').modal('show')
        })
    })

    async function getIngredientsList(sandwichObject) {
        let ingredients = await ingredientsRequest();
        let sandwichIngredientsId = [];
        let sandwichIngredients = [];
        sandwichObject.ingredients.forEach(function (ingredientId) {
            sandwichIngredientsId.push(ingredientId);
        });
        ingredients["hydra:member"].forEach(function (ingredient) {
            if (sandwichIngredientsId.includes(ingredient["@id"])) {
                sandwichIngredients.push(ingredient);
            }
        });
        return sandwichIngredients;
    }

    $(".menu-name").each(function () {
        $(this).on("click", function (e) {
            e.preventDefault()
            toggleDetail(this)
        })
    })

    $(".menu-icon").each(function () {
        $(this).on("click", function (e) {
            e.preventDefault()
            removeMenu(this)
        })
    });

    // Partie supplement/additional product

    $(".product-element").each(function () {
        $(this).on("click", function () {
            let productId = $(this).attr("data-product-id");
            addProductToShopBag({id: productId}).then(function (res) {
                addProductToDom(res);
            }).catch(console.error);
        });
    });

    $(".supr-icon").each(function () {
        $(this).on("click", function (e) {
            e.preventDefault()
            removeProduct(this);
        });
    });

    function addProductToShopBag(data) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/shopbag/addProduct`,
                type: 'POST',
                data: data,
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }

    function addProductToDom(res) {
		console.log(res)
        let $ctn = $("#supp_ctn");

        let $div = $("<div>", {
            class: "add-product-ctn"
        });

        let $span = $("<span>", {
            class: "sub-product",
            text: `1x ${res.product.name} - ${res.product.price}€`
        });

        let $icon = $("<i>", {
            class: "supr-icon fas fa-minus"
        });

        $div.append($span, $icon);

        $icon.on("click", function (e) {
            e.preventDefault()
            removeProduct(this);
        })

        if ($ctn.length == 0) {
            $ctn.append($div);
        } else {
            $ctn.last().after().append($div);
        }
    }

    function removeProduct(obj) {
        // remove in DOM
        $(obj).parent().remove();

        // remove from our shopBag service
        let index = ($additional_product).index(obj);

        //re-indexing
        $additional_product = $(".supr-icon");
        removeProductFromShopbag(index).catch(console.error);
    }

    function removeProductFromShopbag(index) {
        return new Promise(function (resolve, reject) {
            $.ajax({
                url: `http://saladetomateoignons.ddns.net/shopbag/removeProduct/${index}`,
                type: 'DELETE',
                success: function (res) {
                    resolve(res)
                },
                error: function (err) {
                    reject(err)
                }
            });
        });
    }
});
