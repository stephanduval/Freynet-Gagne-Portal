import type { HelpCenterAllCategoryArticles, HelpCenterArticle, HelpCenterArticlesOverview } from '@db/pages/help-center/types'
import checkoutImg from '@images/front-pages/misc/checkout-image.png'
import productImg from '@images/front-pages/misc/product-image.png'

// Images

interface DB {
  allArticles: HelpCenterAllCategoryArticles[]
  keepLearning: HelpCenterArticlesOverview[]
  popularArticles: HelpCenterArticlesOverview[]
  articleData: HelpCenterArticle
}

export const db: DB = {
  popularArticles: [
    {
      slug: 'getting-started',
      title: 'Getting Started',
      img: '../images/svg/rocket.svg',
      subtitle: 'Whether you\'re new or you\'re a power user, this article will',
    },
    {
      slug: 'first-steps',
      title: 'First Steps',
      img: '../images/svg/gift.svg',
      subtitle: 'Are you a new customer wondering how to get started?',
    },
    {
      slug: 'external-content',
      title: 'Add External Content',
      img: '../images/svg/keyboard.svg',
      subtitle: 'Article will show you how to expand functionality of App',
    },
  ],
  allArticles: [
    {
      title: 'Buying',
      icon: 'bx-cart',
      articles: [
        { title: 'What are Favourites?' },
        { title: 'How do I purchase an item?' },
        { title: 'How do i add or change my details?' },
        { title: 'How do refunds work?' },
        { title: 'Can I Get A Refund?' },
        { title: 'I\'m trying to find a specific item' },
      ],
    },
    {
      title: 'Item Support',
      icon: 'bx-help-circle',
      articles: [
        { title: 'What is Item Support?' },
        { title: 'How to contact an author?' },
        { title: 'Where Is My Purchase Code?' },
        { title: 'Extend or renew Item Support' },
        { title: 'Item Support FAQ' },
        { title: 'Why has my item been removed?' },
      ],
    },
    {
      title: 'Licenses',
      icon: 'bx-dollar',
      articles: [
        { title: 'Can I use the same license for the...' },
        { title: 'How to contact an author?' },
        { title: 'I\'m making a test site - it\'s not for ...' },
        { title: 'which license do I need?' },
        { title: 'I want to make multiple end prod ...' },
        { title: 'For logo what license do I need?' },
      ],
    },
    {
      title: 'Template Kits',
      icon: 'bx-brush',
      articles: [
        { title: 'Template Kits' },
        { title: 'Elementor Template Kits: PHP Zip ...' },
        { title: 'Template Kits - Imported template ...' },
        { title: 'Troubleshooting Import Problems' },
        { title: 'How to use the WordPress Plugin ...' },
        { title: 'How to use the Template Kit Import ...' },
      ],
    },
    {
      title: 'Account & Password',
      icon: 'bx-lock-open',
      articles: [
        { title: 'Signing in with a social account' },
        { title: 'Locked Out of Account' },
        { title: 'I\'m not receiving the verification email' },
        { title: 'Forgotten Username Or Password' },
        { title: 'New password not accepted' },
        { title: 'What is Sign In Verification?' },
      ],
    },
    {
      title: 'Account Settings',
      icon: 'bx-user',
      articles: [
        { title: 'How do I change my password?' },
        { title: 'How do I change my username?' },
        { title: 'How do I close my account?' },
        { title: 'How do I change my email address?' },
        { title: 'How can I regain access to my a ...' },
        { title: 'Are RSS feeds available on Market?' },
      ],
    },

  ],
  keepLearning: [
    {
      slug: 'blogging',
      title: 'Blogging',
      img: '../images/svg/laptop.svg',
      subtitle: 'Expert tips & tools to improve your website or online store using blog.',
    },
    {
      slug: 'inspiration-center',
      title: 'Inspiration Center',
      img: '../images/svg/lightbulb.svg',
      subtitle: 'inspiration from experts to help you start and grow your big ideas.',
    },
    {
      slug: 'community',
      title: 'Community',
      img: '../images/svg/discord.svg',
      subtitle: 'A group of people living in the same place or having a particular.',
    },
  ],
  articleData: {
    title: 'How to add product in cart?',
    lastUpdated: '1 month ago  -  Updated',
    productContent: `
            <p>
              If you're after only one item, simply choose the 'Buy Now' option on the item page. This will take you directly to Checkout.
            </p>
            <p>
              If you want several items, use the 'Add to Cart' button and then choose 'Keep Browsing' to continue shopping or 'Checkout' to finalize your purchase.
            </p>
        `,
    checkoutContent: 'You can go back to your cart at any time by clicking on the shopping cart icon at the top right side of the page.',
    articleList: [
      'Template Kits',
      'Elementor Template Kits: PHP Zip Extends',
      'Envato Elements Template Kits',
      'Envato Elements Template Kits',
      'How to use the template in WordPress',
      'How to use the Template Kit Import',
    ],
    checkoutImg,
    productImg,
  },
}
