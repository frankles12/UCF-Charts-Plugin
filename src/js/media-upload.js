/* global wp */
const ucfChartMediaUpload = ($) => {
  let jsonFrame;

  const $metaBox      = $('#ucf_chart_metabox');
  const $addJsonLink  = $metaBox.find('.json-upload');
  const $jsonInput    = $metaBox.find('#ucf_chart_json');
  const $jsonFilename = $metaBox.find('#ucf_chart_json_filename');
  const $jsonPreview  = $metaBox.find('.json-preview');
  const $delJsonLink  = $metaBox.find('.json-remove');

  const addJson = (e) => {
    e.preventDefault();

    if (jsonFrame) {
      jsonFrame.open();
      return;
    }

    jsonFrame = wp.media({
      title: 'Select or upload the json data for this chart.',
      button: {
        text: 'Use this JSON File'
      },
      library: {
        text: 'application/json'
      },
      multiple: false
    });

    jsonFrame.on('select', () => {
      const attachment = jsonFrame.state().get('selection').first().toJSON();
      $jsonPreview.removeClass('hidden');
      $jsonInput.val(attachment.id);
      $jsonFilename.text(attachment.filename);
      $addJsonLink.addClass('hidden');
      $delJsonLink.removeClass('hidden');
    });

    jsonFrame.open();
  };

  const removeMedia = (e) => {
    e.preventDefault();

    $jsonPreview.addClass('hidden');
    $addJsonLink.removeClass('hidden');
    $delJsonLink.addClass('hidden');
    $jsonInput.val('');
    $jsonFilename.text('');
  };

  $addJsonLink.on('click', addJson);
  $delJsonLink.on('click', removeMedia);
};

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(($) => {
    ucfChartMediaUpload($);
  });
}
