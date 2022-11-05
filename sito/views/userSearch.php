<link rel="stylesheet" href="/resources/typeahead/jquery.typeahead.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/resources/typeahead/jquery.typeahead.min.js"></script>

<form id="form-country_v1" name="form-country_v1">
    <div class="typeahead__container">
        <div class="typeahead__field">
            <div class="typeahead__query">
                <input id="search-usrn" class="js-typeahead-country_v1" name="country_v1[query]" placeholder="Search" autocomplete="off">
            </div>
            <div class="typeahead__button">
                <button type="submit">
                    <i class="typeahead__search-icon"></i>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
$.typeahead({
    input: '.js-typeahead-country_v1',
    source: {
        tag: {
            ajax: function (query) {
                return {
                    url: "/search?key="+query,
                }
            }
        }
    },
    dynamic: true,
    minLenght: 3,
    asyncResult: false,
    mustSelectItem: true,
    callback: {
        onInit: function (node) {
            console.log('Typeahead Initiated on ' + node.selector);
        }
    }
});
</script>