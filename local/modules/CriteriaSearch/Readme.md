# Criteria Search

This module add a search engine to your categories page.
With it you can search by some feature or attribute (defined in category edition), by brand, new, promo, stock and price (only TTC for now)

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is CriteriaSearch.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer thelia/criteria-search-module:~1.0
```

## Usage

Simple filter :
---------------

The simple filter are 'new','promo','filter','price' and 'brand'.
Theses filter are configurable only globally for all categories in CriteriaSearch module config.

Complexe filter :
-----------------

Two others kind of filter is by features and by attributes.
For theses filter you have to enabled it by category in category config page under tab modules.


## Hook

3 hooks are created with this module :
1 for search engine css
1 for search engine page
1 for search engine javascript

## Loop

### search_attribute and search_feature

Extends the Thelia loop : [Attribute](http://doc.thelia.net/en/documentation/loop/attribute.html) and [Feature](http://doc.thelia.net/en/documentation/loop/feature.html)
Used to know which attributes is available for search 

#### Additional input arguments

|Argument |Description |
|---      |--- |
|**category** | The category id or list of ids|

#### Additional output arguments

|Variable   |Description |
|---        |--- |
|SEARCHABLE    | Attribute is 'searchable' or not |

#### Exemple
```smarty
    {loop type="search_attribute" name="search_attribute_loop" category=$category_id}
        {if $SEARCHABLE}
            ### Display the attribute
        {/if}
    {/loop}
```

### search_attribute_av and search_feature_av

Extends the Thelia loop : [Attribute](http://doc.thelia.net/en/documentation/loop/attribute_availability.html) and [Feature availability](http://doc.thelia.net/en/documentation/loop/feature_availability.html)
Used to filter attribute_availability by category only if at least one product in this category have this attribute_availability

#### Additional input arguments

|Argument |Description |
|---      |--- |
|**category** | The category id or list of ids|

#### Additional output arguments

No

#### Exemple
```smarty
    {loop type="search_attribute" name="search_attribute_loop" category=$category_id}
        {if $SEARCHABLE}
            {$attributeId = $ID}
            ### IF ATTRIBUTE IS SEARCHABLE
            {ifloop rel="search_attribute_av_loop"}
                <section id="search-attribute" class="block block-nav col-md-3" role="navigation" aria-labelledby="categories-label">
                        <h4  id="categories-label">{$TITLE}</h4>
                        <select class="select-search" id="attribute-{$attributeId}" name="attributes[{$attributeId}][]" multiple="multiple">
                            {loop type="search_attribute_av" name="search_attribute_av_loop" attribute=$ID category=$category_id return_empty=false force_return=true}
                                ### ADD SEARCH INPUT ONLY FOR ATTRIBUTE AVAILABLE IN THIS CATEGORY
                            {/loop}
                        </select>
                </section>
            {/ifloop}
        {/if}
    {/loop}
```

