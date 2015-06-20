# WP-API Post Groups

A [WP-API](http://wp-api.org/) extension that allows multiple groups of posts with different filters to be obtained in a single request.

## What?
I've been experimenting with WordPress as an API-first CMS, as explained [in this article](https://css-tricks.com/thoughts-on-an-api-first-wordpress/). With [WP-API](http://wp-api.org/) maturing into a very stable product and moving to the WordPress core in the near future, it seemed logical to use it rather than creating a custom solution.

However, an API-first approach means that every bit of information from the back-end will have to come through the API, which in some cases might mean sending multiple requests to the API to render a single page. To reduce the overhead caused by multiple HTTP requests, this plugin allows you to receive completely separate sets of data from the API in a single request.

## How?
Currently, the grouping only works with [filters](http://wp-api.org/#posts_retrieve-posts_input) and it's available on two endpoints:

- **/postgroups**: Returns groups of posts
- **/pagegroups**: Returns groups of pages

Each group is defined in the URL with a label and a set of filters in this format: `label:filter[filter_type]=value`. The response is a JSON object with one node per group, named after the label.

## Example

`/postgroups?foo:filter[author]=fooguy&foo:filter[s]=foo&bar:filter[tag]=bar&bar:filter[posts_per_page]=1`

would return:

```
{
	"foo": [
		{
			// Foo post 1
		},
		{
			// Foo post 2
		},
		{
			// Foo post 3
		}
	],
	"bar": [
		{
			// Bar post 1
		}
	]
}
```

## Installation
Download the plugin, place inside `wp-plugins/` and activate.

## Contribute
Feel free to share issues, feature requests or ♥.
