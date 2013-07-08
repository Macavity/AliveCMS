(function(Handlebars) {


  /**
   * Added Helper for creating html lists
   */
  Handlebars.registerHelper('list', function(context, options) {
    var ret = "<ul>";

    for(var i=0, j=context.length; i<j; i++) {
      ret = ret + "<li>" + options.fn(context[i]) + "</li>";
    }

    return ret + "</ul>";
  });

  /**
   * Added Helper for easier usage of precompiled templates as partials
   * instead of {{> partialName}} use {{partial "templateName"}}
   * @param templateName Name of the template that is used as a partial
   * @param context
   */
  Handlebars.registerHelper('partial', function(templateName, context){
    return new Handlebars.SafeString(Handlebars.templates[templateName](this));
  });

}(this.Handlebars));