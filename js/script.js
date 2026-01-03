/* =====================================================
   UI.JS
   Lightweight Bootstrap-like JS + AJAX (Vanilla)
   ===================================================== */

(() => {
  "use strict";

  /* ---------------------------------
     HELPER FUNCTIONS
  ---------------------------------- */
  const $ = (selector, scope = document) => scope.querySelector(selector);
  const $$ = (selector, scope = document) => [...scope.querySelectorAll(selector)];

  const on = (event, selector, handler) => {
    document.addEventListener(event, e => {
      if (e.target.closest(selector)) handler(e);
    });
  };

  /* ---------------------------------
     MODAL
     data-ui-toggle="modal"
     data-ui-target="#modalId"
  ---------------------------------- */
  on("click", "[data-ui-toggle='modal']", e => {
    const target = $(e.target.dataset.uiTarget);
    if (!target) return;
    target.classList.add("show");
    document.body.classList.add("modal-open");
  });

  on("click", "[data-ui-dismiss='modal']", e => {
    const modal = e.target.closest(".modal");
    if (!modal) return;
    modal.classList.remove("show");
    document.body.classList.remove("modal-open");
  });

  /* ---------------------------------
     DROPDOWN
     data-ui-toggle="dropdown"
  ---------------------------------- */
  on("click", "[data-ui-toggle='dropdown']", e => {
    e.preventDefault();
    const menu = e.target.nextElementSibling;
    if (menu) menu.classList.toggle("show");
  });

  document.addEventListener("click", e => {
    $$(".dropdown-menu.show").forEach(menu => {
      if (!menu.parentElement.contains(e.target)) {
        menu.classList.remove("show");
      }
    });
  });

  /* ---------------------------------
     COLLAPSE
     data-ui-toggle="collapse"
     data-ui-target="#id"
  ---------------------------------- */
  on("click", "[data-ui-toggle='collapse']", e => {
    const target = $(e.target.dataset.uiTarget);
    if (target) target.classList.toggle("show");
  });

  /* ---------------------------------
     TABS
     data-ui-toggle="tab"
  ---------------------------------- */
  on("click", "[data-ui-toggle='tab']", e => {
    e.preventDefault();
    const target = $(e.target.dataset.uiTarget);
    if (!target) return;

    const parent = e.target.closest(".tab-group");
    if (!parent) return;

    $$(".tab-pane", parent).forEach(p => p.classList.remove("show", "active"));
    $$(".tab-link", parent).forEach(t => t.classList.remove("active"));

    e.target.classList.add("active");
    target.classList.add("show", "active");
  });

  /* ---------------------------------
     ALERT DISMISS
     data-ui-dismiss="alert"
  ---------------------------------- */
  on("click", "[data-ui-dismiss='alert']", e => {
    const alert = e.target.closest(".alert");
    if (alert) alert.remove();
  });

  /* ---------------------------------
     TOAST
     data-ui-toast="#toastId"
  ---------------------------------- */
  window.showToast = (selector, timeout = 3000) => {
    const toast = $(selector);
    if (!toast) return;

    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), timeout);
  };

  /* =====================================================
     AJAX (NO PAGE RELOAD PHP REQUESTS)
     ===================================================== */

  /* ---------------------------------
     AJAX GET
  ---------------------------------- */
  window.ajaxGet = async (url) => {
    const res = await fetch(url, {
      headers: { "X-Requested-With": "XMLHttpRequest" }
    });
    return res.text();
  };

  /* ---------------------------------
     AJAX POST
  ---------------------------------- */
  window.ajaxPost = async (url, data) => {
    const res = await fetch(url, {
      method: "POST",
      headers: { "X-Requested-With": "XMLHttpRequest" },
      body: data
    });
    return res.text();
  };

  /* ---------------------------------
     AJAX FORM SUBMIT
     data-ui-ajax="true"
  ---------------------------------- */
  on("submit", "form[data-ui-ajax='true']", async e => {
    e.preventDefault();

    const form = e.target;
    const method = form.method.toUpperCase() || "POST";
    const action = form.action;
    const formData = new FormData(form);

    let response;
    if (method === "GET") {
      response = await ajaxGet(action + "?" + new URLSearchParams(formData));
    } else {
      response = await ajaxPost(action, formData);
    }

    const target = form.dataset.uiTarget;
    if (target) $(target).innerHTML = response;
  });

})();
