# Vue 3 and Vuetify 3 Upgrade Notes

## Summary

This document provides information about the upgrade from Vue 2.7.16 to Vue 3.5.22 and Vuetify 2.7.2 to Vuetify 3.10.5.

## Dependency Changes

### Major Updates
- **Vue**: 2.7.16 → 3.5.22
- **Vuetify**: 2.7.2 → 3.10.5
- **@mdi/font**: 5.9.55 → 7.4.47

### Added Dependencies
- **mitt**: 3.0.1 (event bus replacement)
- **@vue/compat**: 3.5.22 (compatibility layer)
- **@vitejs/plugin-vue**: 5.2.1
- **vite-plugin-vuetify**: 2.0.4

### Removed Dependencies
- **vue-template-compiler**: No longer needed in Vue 3
- **vue-i18n**: Removed (not in use)

## Breaking Changes

### Vue 3 Changes

#### Application Initialization
```javascript
// Before (Vue 2)
const app = new Vue({
    vuetify,
    el: '#app',
    // ...
})

// After (Vue 3)
const app = createApp({
    // ...
})
app.use(vuetify)
app.mount('#app')
```

#### Component v-model
```javascript
// Before (Vue 2)
model: {
    prop: 'value',
    event: 'input'
}
props: {
    value: { type: Object }
}
// Emit: this.$emit('input', newValue)

// After (Vue 3)
props: {
    modelValue: { type: Object }
}
// Emit: this.$emit('update:modelValue', newValue)
```

#### Event Bus
```javascript
// Before (Vue 2)
const bus = new Vue()
bus.$emit('event', data)
bus.$on('event', callback)

// After (Vue 3)
import mitt from 'mitt'
const emitter = mitt()
emitter.emit('event', data)
emitter.on('event', callback)
```

### Vuetify 3 Changes

#### Plugin Initialization
```javascript
// Before (Vuetify 2)
import Vuetify from 'vuetify'
Vue.use(Vuetify)
export default new Vuetify({ /* config */ })

// After (Vuetify 3)
import { createVuetify } from 'vuetify'
export default createVuetify({ /* config */ })
```

#### Component Changes

**v-data-table**
- `:options.sync` → `v-model:options`
- `:server-items-length` → `:items-length`

**v-tabs**
- `v-tabs-items` → `v-window`
- `v-tab-item` → `v-window-item`
- Tabs now require explicit `:value` props

**v-list**
- `v-list-item-action` → `template v-slot:prepend`
- `v-list-item-content` → Direct content in `v-list-item`
- `dense` → `density="compact"`

**v-dialog / v-menu**
- Activator slot: `{ on }` → `{ props }`
- Usage: `v-on="on"` → `v-bind="props"`

**v-btn**
- `text` → `variant="text"`
- `icon` → `icon="icon-name"` (now an attribute, not a prop)
- `dark` → Removed
- `fab` → Removed
- Size props: `small` → `size="small"`

**v-select**
- `item-text` → `item-title`
- `item-value` → `item-value` (unchanged)
- `:deletable-chips` → `:closable-chips`

**v-snackbar**
- `top`, `right` → `location="top right"`
- Action buttons now use `v-slot:actions`

**v-icon**
- `small` → `size="small"`

**Color Classes**
- `darken-1` → `-darken-1`
- `lighten-1` → `-lighten-1`

**Removed Props**
- `dense` - Use `density` prop instead
- `offset-y` - No longer needed for v-menu

## Component Access Patterns

### Parent/Root Access
```javascript
// Before (Vue 2)
this.$root.$refs['componentName'].$options.propsData.propName

// After (Vue 3)
this.$parent.propName
```

## Event Handling

```vue
<!-- Before (Vue 2) -->
<v-select @input="handler" />

<!-- After (Vue 3) -->
<v-select @update:model-value="handler" />
```

## Build Configuration

### webpack.mix.js
```javascript
// Before
.vue({ version: 2 })

// After
.vue({ version: 3 })
```

## Testing Checklist

When testing the upgraded application, verify:

- [ ] Application loads without console errors
- [ ] Navigation drawer opens/closes correctly
- [ ] Data tables load and paginate correctly
- [ ] Forms can be created and edited
- [ ] Dialogs open and close properly
- [ ] Date/time pickers work correctly
- [ ] Toast notifications appear correctly
- [ ] Export functionality works
- [ ] Authentication and authorization work
- [ ] All CRUD operations function properly

## Known Issues

- The Sass loader shows a deprecation warning about the legacy JS API. This is expected and doesn't affect functionality. Consider updating to Dart Sass 2.0 in a future update.

## Resources

- [Vue 3 Migration Guide](https://v3-migration.vuejs.org/)
- [Vuetify 3 Migration Guide](https://vuetifyjs.com/en/getting-started/upgrade-guide/)
- [Composition API](https://vuejs.org/guide/extras/composition-api-faq.html)
