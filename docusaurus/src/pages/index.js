/**
 * Copyright (c) 2017-present, Facebook, Inc.
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

import React from 'react';
import classnames from 'classnames';
import Layout from '@theme/Layout';
import Link from '@docusaurus/Link';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import withBaseUrl from '@docusaurus/withBaseUrl';
import styles from './styles.module.css';

const features = [
  {
    title: <>Laravel</>,
    imageUrl: 'img/undraw_docusaurus_mountain.svg',
    description: (
      <>
        <code>PHP</code>流行框架<code>Laravel</code>的扩展包。
      </>
    ),
  },
  {
    title: <>AdminTle</>,
    imageUrl: 'img/undraw_docusaurus_tree.svg',
    description: (
      <>
        前端使用<code>Bootstrap</code>框架定制的模板套件<code>AdminTle</code>。
      </>
    ),
  },
  {
    title: <>RBAC</>,
    imageUrl: 'img/undraw_docusaurus_react.svg',
    description: (
      <>
        集成提供从视图到程序的<code>RBAC</code>功能。
      </>
    ),
  },
  {
    title: <>多语言</>,
    imageUrl: 'img/undraw_docusaurus_mountain.svg',
    description: (
      <>
        多语言化支持。
      </>
    ),
  },
  {
    title: <>文件管理器</>,
    imageUrl: 'img/undraw_docusaurus_tree.svg',
    description: (
      <>
        集成<code>Elfinder</code>流行WEB文件管理器。
      </>
    ),
  },
];

function Home() {
  const context = useDocusaurusContext();
  const {siteConfig = {}} = context;
  return (
    <Layout
      title={`${siteConfig.title}`}
      description="Description will go into a meta tag in <head />">
      <header className={classnames('hero hero--primary', styles.heroBanner)}>
        <div className="container">
          <h1 className="hero__title">{siteConfig.title}</h1>
          <p className="hero__subtitle">{siteConfig.tagline}</p>
          <div className={styles.buttons}>
            <Link
              className={classnames(
                'button button--outline button--secondary button--lg',
                styles.getStarted,
              )}
              to={withBaseUrl('docs/introduction')}>
              Get Started
            </Link>
          </div>
        </div>
      </header>
      <main>
        {features && features.length && (
          <section className={styles.features}>
            <div className="container">
              <div className="row">
                {features.map(({imageUrl, title, description}, idx) => (
                  <div
                    key={idx}
                    className={classnames('col col--4', styles.feature)}>
                    {imageUrl && (
                      <div className="text--center">
                        <img
                          className={styles.featureImage}
                          src={withBaseUrl(imageUrl)}
                          alt={title}
                        />
                      </div>
                    )}
                    <h3>{title}</h3>
                    <p>{description}</p>
                  </div>
                ))}
              </div>
            </div>
          </section>
        )}
      </main>
    </Layout>
  );
}

export default Home;
