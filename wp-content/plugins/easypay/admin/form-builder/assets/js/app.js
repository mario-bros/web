define([
    "jquery", "underscore", "backbone"
            , "collections/snippets", "collections/my-form-snippets"
            , "views/tab", "views/my-form"
            , "text!data/input.json", "text!data/radio.json", "text!data/select.json", "text!data/buttons.json"
            , "text!templates/app/render.html", "text!templates/app/about.html", "text!templates/app/renderjson.html",
], function(
        $, _, Backbone
        , SnippetsCollection, MyFormSnippetsCollection
        , TabView, MyFormView
        , inputJSON, radioJSON, selectJSON, buttonsJSON
        , renderTab, aboutTab, jsonTab
        ) {
    return {
        initialize: function() {

            //Bootstrap tabs from json.
            new TabView({
                title: "Input"
                , collection: new SnippetsCollection(JSON.parse(inputJSON))
            });
            new TabView({
                title: "Radio / Checkbox"
                , collection: new SnippetsCollection(JSON.parse(radioJSON))
            });
            new TabView({
                title: "Select"
                , collection: new SnippetsCollection(JSON.parse(selectJSON))
            });
            /*new TabView({
                title: "Button"
                , collection: new SnippetsCollection(JSON.parse(buttonsJSON))
            });*/
            new TabView({
                title: "HTML"
                , content: renderTab
            });
            new TabView({
                title: "JSON"
                , content: jsonTab
            });
            /*new TabView({
             title: "About"
             , content: aboutTab
             });*/

            //Make the first tab active!
            $("#components .tab-pane").first().addClass("active");
            $("#formtabs li").first().addClass("active");
            // Bootstrap "My Form" with 'Form Name' snippet.
            var formView = new MyFormView({
                title: "Original"
                , collection: new MyFormSnippetsCollection(saved_form)
            });

            $('#loadJSON').on('click', function() {
                event.preventDefault();
                var value = $("#jsonrender").val();
                var json = $.parseJSON(value);
                formView.collection = new MyFormSnippetsCollection(json);
                formView.initialize();
            });
        }
    }
});
