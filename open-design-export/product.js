(() => {
  const body = document.body;
  const menuButton = document.getElementById("menu-toggle");
  const menu = document.getElementById("site-menu");
  const scrim = document.getElementById("drawer-scrim");
  const toast = document.getElementById("toast");
  let toastTimer;

  function notify(message) {
    if (!toast) return;
    clearTimeout(toastTimer);
    toast.textContent = message;
    toast.classList.add("show");
    toastTimer = window.setTimeout(() => toast.classList.remove("show"), 2400);
  }

  function setMenu(open) {
    if (!menuButton || !scrim) return;
    body.classList.toggle("menu-open", open);
    menuButton.setAttribute("aria-expanded", String(open));
    scrim.classList.toggle("is-visible", open);
  }

  if (menuButton && menu && scrim) {
    menuButton.addEventListener("click", () => {
      setMenu(menuButton.getAttribute("aria-expanded") !== "true");
    });
    scrim.addEventListener("click", () => setMenu(false));
    menu.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setMenu(false));
    });
    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") setMenu(false);
    });
  }

  document.querySelectorAll("[data-toast]").forEach((control) => {
    control.addEventListener("click", () => notify(control.dataset.toast));
  });

  const savedSearch = document.getElementById("saved-search");
  const savedFilterButtons = Array.from(document.querySelectorAll("[data-saved-filter]"));
  const savedLibrary = document.getElementById("saved-library");
  const savedEmpty = document.getElementById("saved-empty");
  let activeSavedFilter = "all";

  function savedCards() {
    return Array.from(document.querySelectorAll("[data-saved-card]"));
  }

  function updateSavedLibrary() {
    if (!savedLibrary || !savedEmpty) return;
    const query = savedSearch ? savedSearch.value.trim().toLowerCase() : "";
    let visibleCount = 0;
    const cards = savedCards();

    cards.forEach((card) => {
      const category = card.dataset.category || "";
      const searchable = [
        card.dataset.title,
        card.dataset.summary,
        category,
        card.textContent,
      ].join(" ").toLowerCase();
      const categoryMatch = activeSavedFilter === "all" || category === activeSavedFilter;
      const queryMatch = !query || searchable.includes(query);
      const visible = categoryMatch && queryMatch;
      card.hidden = !visible;
      if (visible) visibleCount += 1;
    });

    document.querySelectorAll("[data-saved-group]").forEach((group) => {
      group.hidden = !group.querySelector("[data-saved-card]:not([hidden])");
    });

    const emptyTitle = savedEmpty.querySelector("strong");
    const emptyCopy = savedEmpty.querySelector("p");
    if (cards.length === 0) {
      if (emptyTitle) emptyTitle.textContent = "No saved signals yet.";
      if (emptyCopy) emptyCopy.textContent = "Save important updates from Today’s Radar to build your intelligence library.";
    } else {
      if (emptyTitle) emptyTitle.textContent = "No saved signals match this view.";
      if (emptyCopy) emptyCopy.textContent = "Try a different category or search term to recover more context.";
    }
    savedLibrary.hidden = visibleCount === 0;
    savedEmpty.hidden = visibleCount !== 0;
  }

  if (savedSearch && savedFilterButtons.length) {
    savedSearch.addEventListener("input", updateSavedLibrary);
    savedFilterButtons.forEach((button) => {
      button.addEventListener("click", () => {
        activeSavedFilter = button.dataset.savedFilter || "all";
        savedFilterButtons.forEach((item) => {
          item.setAttribute("aria-pressed", String(item === button));
        });
        updateSavedLibrary();
      });
    });
    document.querySelectorAll("[data-remove-card]").forEach((button) => {
      button.addEventListener("click", () => {
        const card = button.closest("[data-saved-card]");
        if (!card) return;
        card.classList.add("is-removing");
        window.setTimeout(() => {
          card.remove();
          updateSavedLibrary();
          notify("Saved signal removed.");
        }, 180);
      });
    });
    updateSavedLibrary();
  }

  document.querySelectorAll("[data-mark-read]").forEach((control) => {
    control.addEventListener("click", () => {
      control.textContent = "Read";
      control.setAttribute("aria-pressed", "true");
      notify("Signal marked as read.");
    });
  });

  document.querySelectorAll("[data-share]").forEach((control) => {
    control.addEventListener("click", async () => {
      const shareText = "Tech Context Radar: OpenAI improves coding agent workflow";
      const shareUrl = window.location.href;
      try {
        if (navigator.share) {
          await navigator.share({ title: shareText, url: shareUrl });
          notify("Share sheet opened.");
          return;
        }
        if (navigator.clipboard) {
          await navigator.clipboard.writeText(shareUrl);
          notify("Signal link copied.");
          return;
        }
      } catch (_) {
        // Fall through to the non-blocking toast below.
      }
      notify("Signal ready to share.");
    });
  });

  document.querySelectorAll(".tab").forEach((tab) => {
    tab.addEventListener("click", () => {
      const group = tab.closest(".tabs");
      if (!group) return;
      group.querySelectorAll(".tab").forEach((item) => item.setAttribute("aria-selected", "false"));
      tab.setAttribute("aria-selected", "true");
      notify(tab.textContent.trim() + " view selected.");
    });
  });

  document.querySelectorAll(".switch").forEach((toggle) => {
    toggle.addEventListener("click", () => {
      const next = toggle.getAttribute("aria-pressed") !== "true";
      toggle.setAttribute("aria-pressed", String(next));
      notify((toggle.dataset.label || "Preference") + (next ? " enabled." : " disabled."));
    });
  });

  document.querySelectorAll(".range").forEach((range) => {
    range.addEventListener("input", () => {
      document.querySelectorAll('[data-range-output="' + range.id + '"]').forEach((output) => {
        output.textContent = range.value + " min";
      });
    });
  });
})();
