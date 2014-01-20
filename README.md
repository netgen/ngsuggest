Netgen Suggest
==============

This extension implements drop down suggestions on search fields
using eZ Find/Solr facets. When user starts to type letters in search
box suggestions are shown as drop down bellow. Suggestions are sorted
by number of occurance in index. When next word is entered new
suggestions are based on words before.

Extension is based on jQuery component and runs smoothly with eZ JS Core.

For better faceting results we created new type of field for Solr.
Existing `ezf_df_text` can be used but it likes to concatenate words
so suggestions will not be relevant.
