var site_colors = [
  'legacy',
  'progressive',
  'credible',
  'confident',
  'professional',
  'empowering',
  'passionate',
  'dynamic',
  'inspired',
  'white',
];


var dataSet = [{
  name: 'Small',
  element: 'small'
}, {
  name: 'Computer Code',
  element: 'code'
}, {
  name: 'Deleted Text',
  element: 'del'
}, {
  name: 'Inserted Text',
  element: 'ins'
}, {
  name: 'Cited Work',
  element: 'cite'
}];

var newItem;

var capitalizeFirstLetter = function(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
};

for (i = 0; i < site_colors.length; i++) {

  newItem = {
    name: capitalizeFirstLetter(site_colors[i]),
    element: 'p',
    attributes: {
      class: 'color-' + site_colors[i]
    }
  };

  dataSet.push(newItem);
}


CKEDITOR.addStylesSet('my_styles', dataSet);

