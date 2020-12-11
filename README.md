# WPGraphQL Ninja Forms

A WordPress plugin that Adds Ninja Forms functionality to WPGraphQL schema.

**WARNING:** this plugin is under development and it's not production ready!

## Installation
 
2. Install and activate [WPGraphQL](https://www.wpgraphql.com/) wordpress plugin
2. Install and activate [Ninja Forms](https://wordpress.org/plugins/ninja-forms/) wordpress plugin
1. Clone or download the zip of this repository into your WordPress plugin directory and activate **WPGraphQL Ninja Forms**

## Features

* Query **forms** with their related fields
* Submit one or multiple form entries using mutations

## Get a form and its fields

```graphql
{
  form(id: "1", idType: DATABASE_ID) {
    title,
    fields {
      nodes {
        fieldId
        label
        type
      }
    }
  }
}
```

## Submit a form entry

```graphql
{
  submitForm(input: {formId: 1, data: [
    {id: 1, value: "Julius"}, 
    {id: 2, value: "julius@test.com"}, 
    {id: 3, value: "Hello there"}
  ]}) {
    errors {
      fieldId
      message
      slug
    }
    message
    success
  }
}
```