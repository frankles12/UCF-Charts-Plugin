/* global wp */
const ucfChartMediaUpload = ($) => {
  let dataJsonFrame;
  let optsJsonFrame;

  const $metaBox      = $('#ucf_chart_metabox');
  const $addDataJsonLink  = $metaBox.find('.data-json-upload');
  const $dataJsonInput    = $metaBox.find('#ucf_chart_data_json');
  const $dataJsonFilename = $metaBox.find('.ucf_chart_data_json_filename');
  const $dataJsonPreview  = $metaBox.find('.data-json-preview');
  const $delDataJsonLink  = $metaBox.find('.data-json-remove');

  const $addOptsJsonLink  = $metaBox.find('.options-json-upload');
  const $optsJsonInput    = $metaBox.find('#ucf_chart_options_json');
  const $optsJsonFilename = $metaBox.find('.ucf_chart_options_json_filename');
  const $optsJsonPreview  = $metaBox.find('.options-json-preview');
  const $delOptsJsonLink  = $metaBox.find('.options-json-remove');

  const createFrame = (e) => {
    let dataJson = false;
    e.preventDefault();

    if ($(e.target).hasClass('data-json-upload')) {
      dataJson = true;
    } else {
      dataJson = false;
    }

    if (dataJson) {
      if (dataJsonFrame) {
        dataJsonFrame.open();
        return;
      }
    } else if (optsJsonFrame) {
      optsJsonFrame.open();
      return;
    }

    let title;
    const btnText = 'Use this JSON File';
    const fileType = 'application/json';
    const args = {
      title,
      button: {
        text: btnText
      },
      library: {
        type: fileType
      },
      multiple: false
    };

    if (dataJson) {
      title = 'Select or upload the json data for this chart.';
    } else {
      title = 'Select or upload the json options for this chart.';
    }

    if (dataJson) {
      dataJsonFrame = wp.media(args);

      dataJsonFrame.on('select', () => {
        const attachment = dataJsonFrame.state().get('selection').first().toJSON();
        $dataJsonPreview.removeClass('hidden');
        $dataJsonInput.val(attachment.id);
        $dataJsonFilename.text(attachment.filename);
        $addDataJsonLink.addClass('hidden');
        $delDataJsonLink.removeClass('hidden');
      });

      dataJsonFrame.open();
    } else {
      optsJsonFrame = wp.media(args);

      optsJsonFrame.on('select', () => {
        const attachment = optsJsonFrame.state().get('selection').first().toJSON();
        $optsJsonPreview.removeClass('hidden');
        $optsJsonInput.val(attachment.id);
        $optsJsonFilename.text(attachment.filename);
        $addOptsJsonLink.addClass('hidden');
        $delOptsJsonLink.removeClass('hidden');
      });

      optsJsonFrame.open();
    }
  };

  const removeMedia = (e) => {
    e.preventDefault();

    const $target = $(e.target);
    const isData = $target.hasClass('data-json-remove');

    if (isData) {
      $dataJsonPreview.addClass('hidden');
      $addDataJsonLink.removeClass('hidden');
      $delDataJsonLink.addClass('hidden');
      $dataJsonInput.val('');
      $dataJsonFilename.text('');
    } else {
      $optsJsonPreview.addClass('hidden');
      $addOptsJsonLink.removeClass('hidden');
      $delOptsJsonLink.addClass('hidden');
      $optsJsonInput.val('');
      $optsJsonFilename.text('');
    }
  };

  $addDataJsonLink.on('click', createFrame);
  $delDataJsonLink.on('click', removeMedia);

  $addOptsJsonLink.on('click', createFrame);
  $delOptsJsonLink.on('click', removeMedia);
};

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(($) => {
    ucfChartMediaUpload($);
  });
}
