import { defineAsyncComponent } from "vue";
// prettier-ignore
export default {
  TInnerLoading: defineAsyncComponent(() => import("./TInnerLoading.vue")),
  TIcon: defineAsyncComponent(() => import("./TIcon.vue")),
  TFileIcon: defineAsyncComponent(() => import("./TFileIcon.vue")),
  TButton: defineAsyncComponent(() => import("./TButton.vue")),
  TCollapse: defineAsyncComponent(() => import("./TCollapse.vue")),
  TImage: defineAsyncComponent(() => import("./TImage.vue")),
  TToolTip: defineAsyncComponent(() => import("./TToolTip.vue")),
  TMenu: defineAsyncComponent(() => import("./TMenu.vue")),
  TPagination: defineAsyncComponent(() => import("./TPagination.vue")),

  // Layout
  Layout: defineAsyncComponent(() => import("./Layout/Layout.vue")),
  Header: defineAsyncComponent(() => import("./Layout/Header.vue")),
  Footer: defineAsyncComponent(() => import("./Layout/Footer.vue")),
  PageContainer: defineAsyncComponent(() =>
    import("./Layout/PageContainer.vue")
  ),
  Page: defineAsyncComponent(() => import("./Layout/Page.vue")),
  SideBar: defineAsyncComponent(() => import("./Layout/SideBar.vue")),
  // End - Layout

  // Card
  TCard: defineAsyncComponent(() => import("./Card/TCard.vue")),
  TCardHeader: defineAsyncComponent(() => import("./Card/TCardHeader.vue")),
  TCardBody: defineAsyncComponent(() => import("./Card/TCardBody.vue")),
  TCardFooter: defineAsyncComponent(() => import("./Card/TCardFooter.vue")),
  TCardTitle: defineAsyncComponent(() => import("./Card/TCardTitle.vue")),
  TDialog: defineAsyncComponent(() => import("./TDialog.vue")),
  // End - Card

  // Forms
  TInput: defineAsyncComponent(() => import("./Forms/TInput.vue")),
  TFieldWrapper: defineAsyncComponent(() =>
    import("./Forms/TFieldWrapper2.vue")
  ),
  TTextArea: defineAsyncComponent(() => import("./Forms/TTextArea.vue")),
  TCheckBox: defineAsyncComponent(() => import("./Forms/TCheckBox.vue")),
  TRadio: defineAsyncComponent(() => import("./Forms/TRadio.vue")),
  TList: defineAsyncComponent(() => import("./Forms/TList.vue")),
  TSelect: defineAsyncComponent(() => import("./Forms/TSelect.vue")),
  TImageSelect: defineAsyncComponent(() => import("./Forms/TImageSelect.vue")),
  TDate: defineAsyncComponent(() => import("./Forms/TDate.vue")),
  TFileDrop: defineAsyncComponent(() => import("./Forms/TFileDrop.vue")),
  TFileInfo: defineAsyncComponent(() =>
    import("./Forms/TFileDrop/FileInfo.vue")
  ),
  // End - Forms

  // Spinners
  TSpinnerOrbit: defineAsyncComponent(() => import("./Spinners/orbit.vue")),
  TSpinnerCircles: defineAsyncComponent(() =>
    import("./Spinners/spinningCircles.vue")
  ),
  // End - Spinners

  // Utils
  SizeObserver: defineAsyncComponent(() => import("./SizeObserver.vue")),
  // End - Utils

  // TProgress
  TProgress: defineAsyncComponent(() => import("./TProgress.vue")),
  // End - TProgress

  // Others
  ThemeToggle: defineAsyncComponent(() => import("./others/ThemeToggle.vue")),
  FocusHelper: defineAsyncComponent(() => import("./others/FocusHelper.vue")),
  TScrollUp: defineAsyncComponent(() => import("./TScrollUp.vue")),
  TGroup: defineAsyncComponent(() => import("./others/TGroup.vue")),
  TAddress: defineAsyncComponent(() => import("./others/TAddress.vue")),
  TSearcher: defineAsyncComponent(() => import("./others/TSearcher.vue")),
  TSearchButton: defineAsyncComponent(() =>
    import("./others/TSearchButton.vue")
  ),
  ErrorTemplate: defineAsyncComponent(() =>
    import("./others/ErrorTemplate.vue")
  ),
  // End - Others
};
