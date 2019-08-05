import '@babel/polyfill';
import '../css/dashboard.scss';
import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
const moment = require('moment');
import AppWidgetCustomizer from './component/dashboard/AppWidgetCustomizerRedux';

//render app widget customizer
const appWidgetCustomizerRoot = document.getElementById('app-widget-customizer-root');
if (appWidgetCustomizerRoot)
  ReactDOM.render(<AppWidgetCustomizer/>, appWidgetCustomizerRoot);

$(window).on('load', function() {
  $("body").removeClass("preload");
});

$(document).ready(function(){
  //nav pin button
  $('.dashboard-nav-pin-btn').click(function(){
    $('body').toggleClass('nav-pin');

    if ($('body').hasClass('nav-pin'))
      document.cookie = "navPin=true;";
    else
      document.cookie = "navPin=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
  });

  //nav submenus
  $('.has-submenu').click(function(){
    $('.has-submenu').not(this).removeClass('is-open');
    $(this).toggleClass('is-open');
    return false;
  });

  //profile menu
  $('.profile-avatar').click(function(){
    $(this).parent().toggleClass('is-open');
    event.stopPropagation();
  });

  $('.profile-menu').click(function(){
    event.stopPropagation();
  });

  //tooltips
  tippy('.tooltip', {
    placement: 'right',
    arrow: true,
    delay: 500
  });

  //body offclick shared function
  $('body').click(function(e){
    $('.dashboard-profile').removeClass('is-open');
    $('.editable-text-wrapper').removeClass('is-unlocked');
    $('.editable-text').prop('readonly', 'readonly');
  });

  //close feedback by user
  $('.feedback-close-btn').on('click', function(){
    $(this).closest('.feedback-wrapper').removeClass('visible');
  });

  //close feedback on timer
  setTimeout(function(){
    $('.feedback-wrapper').removeClass('visible');
  }, 5000);

  $('.feedback-wrapper').addClass('visible');

  //invalid form fields
  formValidate();
  function formValidate()
  {
    const invalidClassName = 'invalid';
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function (input)
    {
      // Add a css class on submit when the input is invalid.
      input.addEventListener('invalid', ()  => {
        input.classList.add(invalidClassName);
      });

      // Remove the class when the input becomes valid.
      input.addEventListener('input', () => {
        if (input.validity.valid)
          input.classList.remove(invalidClassName);
        else
          input.classList.add(invalidClassName);
      });
    });
  }

  const formSubmitButtons = document.querySelectorAll('input[type=submit], button[type=submit]');
  formSubmitButtons.forEach(function (input)
  {
    input.addEventListener('click', () => {
      formValidate();
    });
  });

  $('.logo-remove-btn').click(async function(){
    let rsp = await axios.patch('/api/v1/settings', {
      logo: ''
    });

    if (rsp && rsp.status == 200 && !rsp.data.error)
      $('.logo-preview-container').remove();
  });

  //create collections
  let incidentServicesCollection = new ServiceCollection('#incident-services-group', 2);
  let incidentUpdatesCollection = new ServiceCollection('#incident-updates-group', 1);
  let maintenanceServicesCollection = new ServiceCollection('#maintenance-services-group', 2);
  let maintenanceUpdatesCollection = new ServiceCollection('#maintenance-updates-group', 1);

  //add delete button to collection item forms
  $('.collection-subform .row').not('.collection-header').each(function(){
    $(this).append('<button class="collection-delete-btn"></button>');
  });

  //add delete button click event
  $('.collection-subform').on('click', '.collection-delete-btn', function(){
    $(this).parent().remove();
  });

  //incident updates - add datetimepicker to inputs
  $('#incident-updates-group .editable-text').each(function(){
    $(this).addClass('datetimepicker');
    $(this).prop('autocomplete', 'off');
  });

  //maintenance updates - add datetimepicker to inputs
  $('#maintenance-updates-group .editable-text').each(function(){
    $(this).addClass('datetimepicker');
    $(this).prop('autocomplete', 'off');
  });

  //editable text inputs keep body click from happening if on element
  //so date can be changed
  $('.editable-text-wrapper').click(function(){
    event.stopPropagation();
  });

  //for new fields, not sure why I need two still
  $('form').on('click', '.editable-text-wrapper', function(){
    event.stopPropagation();
  })

  //add editable text activation to existing rows
  $('.editable-text-action').on('click', function(){
    var textField = $(this).next('.editable-text');
    textField.parent().toggleClass('is-unlocked');
    textField.prop('readonly', function (_, val) { return !val; });
    return false;
  });

  //add editable text activation to new rows
  $('form').on('click', '.editable-text-action', function(){
    var textField = $(this).next('.editable-text');
    textField.parent().toggleClass('is-unlocked');
    textField.prop('readonly', function (_, val) { return !val; });
    return false;
  });

  //datetimepicker inputs
  $('.datetimepicker').datetimepicker();

  //datatables
  const subscriptionViewDataTable = $('#subscription-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    language: {
      emptyTable: 'No one has subscribed yet'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const userViewDataTable = $('#user-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    language: {
      emptyTable: 'Div by 0 ... not possible'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const serviceStatusViewDataTable = $('#service-status-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'Div by 0 ... not possible'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const custommetricViewDataTable = $('#custommetric-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 2,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'No metrics available'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const maintenanceStatusViewDataTable = $('#maintenance-status-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'Div by 0 ... not possible'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const incidentStatusViewDataTable = $('#incident-status-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [{
      targets: 1,
      orderable: false
    }],
    order: [],
    language: {
      emptyTable: 'Div by 0 ... not possible'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const incidentViewDataTable = $('#incident-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [
      {
        targets: 1,
        width: '120px'
      },
      {
        targets: 2,
        orderable: false,
        width: '140px'
      }
    ],
    autoWidth: false,
    order: [],
    language: {
      emptyTable: 'No incidents reported'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const maintenanceViewDataTable = $('#maintenance-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [
      {
        targets: 1,
        width: '120px'
      },
      {
        targets: 2,
        orderable: false,
        width: '140px'
      }
    ],
    autoWidth: false,
    order: [],
    language: {
      emptyTable: 'Nothing scheduled'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const serviceViewDataTable = $('#service-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [
      {
        targets: 1,
        orderable: false,
        width: '130px'
      }
    ],
    autoWidth: false,
    order: [],
    language: {
      emptyTable: 'Nothing to report here'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  const serviceCategoryViewDataTable = $('#servicecategory-view-table').DataTable({
    paging: false,
    info: false,
    columnDefs: [
      {
        targets: 1,
        orderable: false,
        width: '130px'
      }
    ],
    autoWidth: false,
    order: [],
    language: {
      emptyTable: 'No categories created'
    },
    initComplete: function() {
      $(this).removeClass('is-hidden');
    }
  });

  //delete records ajax
  $('#subscription-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/subscriptions/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            subscriptionDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#user-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/users/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            userDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });

  $('#service-view-list').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('.list-item');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/services/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            parent.remove();
          }, 200);
        }
      }
    );
  });

  $('#service-category-view-list').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('.list-item');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/servicecategories/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            parent.remove();
          }, 200);
        }
      }
    );
  });

  $('#incident-view-list').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('.list-item');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/incidents/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            parent.remove();
          }, 200);
        }
      }
    );
  });

  $('#maintenance-view-table').on('click', '.btn-delete', function(){
    confirmDelete(
      async () => {
        let parent = $(this).closest('tr');
        let dataID = parent.data('id');

        let rsp = await axios.delete('/api/v1/maintenance/' + dataID);

        if (rsp.status == 200 && !rsp.data.error)
        {
          parent.addClass('is-deleting');
          setTimeout(function(){
            maintenanceViewDataTable.row(parent).remove().draw();
          }, 200);
        }
      }
    );
  });
});

function confirmDelete(success, cancel)
{
  $.confirm({
    title: 'Are you sure?',
    content: 'Delete this item',
    type: 'red',
    theme: 'supervan',
    buttons: {
      ok: {
        text: 'Delete!',
        btnClass: 'btn-red',
        action: function() {
          if (success)
            success();
        }
      },
      cancel: {
        text: 'Cancel',
        action: function() {
          if (cancel)
            cancel();
        }
      }
    }
  });
}

class ServiceCollection
{
  constructor(collectionGroupNode, columns)
  {
    this.collectionGroup = $(collectionGroupNode);
    this.columns = columns;
    this.collectionSubForm = this.collectionGroup.find('.collection-subform');
    this.collectionSubForm.data('index', this.collectionSubForm.find('.form-control').length);

    this.collectionGroup.on('click', '.collection-add-btn', () => {
      this.addForm();
      return false;
    });
  }

  addForm()
  {
    let prototype = this.collectionSubForm.data('prototype');
    let index = this.collectionSubForm.data('index');

    this.collectionSubForm.data('index', index + 1);

    //replace __name__ with index
    prototype = prototype.replace(/__name__/g, index);

    if (this.columns == 2)
      prototype = prototype.replace(/form-group/g, 'form-group col-sm-6');
    else if (this.columns == 1)
      prototype = prototype.replace(/form-group/g, 'form-group col-sm-12');

    //add [row] class
    let formRow = $(prototype).addClass('row');

    //add row deletion button
    formRow.append('<button class="collection-delete-btn"></button>');

    //add extra custom  attributes if needed to subform row
    formRow = this.addExtraAttributes(formRow);

    //append to collection list
    this.collectionSubForm.append(formRow);
  }

  addExtraAttributes(formRow)
  {
    const subformId = this.collectionSubForm.parent().attr('id');

    if (subformId == 'maintenance-updates-group'
      || subformId == 'incident-updates-group'
    )
    {
      const currentDatetime = moment().format('MM/DD/YYYY h:mm A');
      formRow.find('.editable-text').val(currentDatetime);
    }

    return formRow;
  }
}
