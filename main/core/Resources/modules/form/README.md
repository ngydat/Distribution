
Basic form
============

Render a form
-------------------------

```
    this.formField = {
      name: 'field-facet',
      translation_domain: 'platform',
      fields: [
        { type: 'text', name: 'name', label: 'name' },
        {
          type: 'select',
          name: 'type',
          label: 'type',
          options: {
            values: [
              // these values currently come from the Entity/Facet/FieldFacet class
              { value: 1, label: 'text'},
              { value: 2, label: 'number'},
              { value: 3, label: 'date'},
              { value: 4, label: 'radio'},
              { value: 5, label: 'select'},
              { value: 6, label: 'checkbox'},
              { value: 7, label: 'country'}
            ]
          }
        },
        { type: 'checkbox', name: 'is_visible_by_owner', label: 'visible'},
        { type: 'checkbox', name: 'is_editable_by_owner', label: 'editable'}
      ]
    }
```

```
    <formbuilder form="mc.form" ng-model="mc.model"></formbuilder>
```

Render a text field
--------------------
```
    <form-text field="field" ng-model="model" form-name="my_name"></form-text>
```

Render a select field
--------------------
```
    <form-select field="field" ng-model="model" form-name="my_name"></form-select>
```

Render a checkbox field
--------------------
```
    <form-checkbox field="field" ng-model="model" form-name="my_name"></form-checkbox>
```

Form submission
----------------

```
    const data = this.FormBuilderService.formSerialize('myFormName', myModel)

    this.$http.post(
      Routing.generate('this_is_a_route'),
      data,
      {headers: {'Content-Type': 'application/x-www-form-urlencoded'}}
    ).then(
      d => {
        //it worked !!
      },
      d => {
        //it failed !!
      }
    )
```

Available validators
----------
- not-blank

More specific use
=================

There is a directive for handling checkboxes array (no form field yet)

```
    <tr ng-repeat="(key, platformRole) in platformRoles track by platformRole.id">
        <td> {{ platformRole.translation_key|trans:{}:'platform' }} </td>
        <td> <input type="checkbox" checklist-model="roles" checklist-value="platformRole"> </input> </td>
    </tr>
```

- checklist-value is the value of the input
- checklist-model is the model array

TODO
=====
- Form validation
