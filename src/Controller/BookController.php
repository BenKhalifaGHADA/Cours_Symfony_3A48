<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;

use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/addBook',name: 'addBook')]
    public function addBook(Request $request,ManagerRegistry $manager){
        $book=new Book();
        $form=$this->createForm(BookType::class,$book);
        $form->add('Add',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$manager->getManager();
            $book->setPublished(true);
            $book->getAuthor()->setNbBooks($book->getAuthor()->getNbBooks()+1);
            $em->persist($book);
            $em->flush();
            return new Response('Book added');
        }


        return $this->render('book/add.html.twig',['formulaire'=>$form->createView()]);
    }
    #[Route('/deleteBook/{id}',name: 'deleteBook')]
    public function deleteBook($id,BookRepository $repo, ManagerRegistry $manager){
        //find($id) ou findBy
        $book=$repo->find($id);
        // remove($obj) /flush()
        $em=$manager->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('showAllBook');
    }

    #[Route('/showAllBook',name: 'showAllBook')]
    public function showAllBook(BookRepository $repo){
        $list=$repo->findAll();
        return $this->render('book/list.html.twig',['books'=>$list]);
    }
    #[Route('/showDetails/{id}',name:'showDetails')]
    public function showDetails($id,BookRepository $repo){
        $book=$repo->find($id);
        return $this->render('book/showDetails.html.twig',['book'=>$book]);


    }
    #[Route('/editBook/{id}',name:'editBook')]
    public function editBook($id,BookRepository $repo,ManagerRegistry $manager,Request $request){
        $book=$repo->find($id);
        $form=$this->createForm(BookType::class,$book);
        $form->add('Save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em = $manager->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showAllBook');

        }
        return $this->render('book/editBook.html.twig',['form'=>$form->createView()]);

    }

    #[Route('/showBooksQueryBuilder',name: 'showBooksQueryBuilder')]
    public function showBooksQueryBuilder(BookRepository $repo){
        $list=$repo->showBooksQB();
        return $this->render('book/list.html.twig',['books'=>$list]);
    }

    #[Route('/showBooksDQL',name: 'showBooksDQL')]
    public function showBooksDQL(BookRepository $repo){
        $list=$repo->showBookDQL();
        return $this->render('book/list.html.twig',['books'=>$list]);
    }

    //Query Builder: Question 2
    #[Route('/book/list/search', name: 'app_book_search', methods: ['GET', 'POST'])]
    public function searchBookByRef(Request $request, BookRepository $bookRepository): Response
    {
        $book = new Book();
        $form = $this->createForm(SearchBookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            return $this->render('book/listSearch.html.twig', [
                'books' => $bookRepository->showAllBooksByRef($book->getRef()),
                'f' => $form->createView()
            ]);
        }
        return $this->render('book/listSearch.html.twig', [
            'books' => $bookRepository->findAll(),
            'f' => $form->createView()
        ]);
    }

    //Query Builder: Question 4
    #[Route('/book/list/QB', name: 'app_book_list_author_date', methods: ['GET'])]
    public function showBooksByDateAndNbBooks(BookRepository $bookRepository): Response
    {
        return $this->render('book/listBookDateNbBooks.html.twig', [
            'books' => $bookRepository->showBooksByDateAndNbBooks(10, '2023-01-01'),
        ]);
    }

    //Query Builder: Question 5
    #[Route('/book/list/author/update/{category}', name: 'app_book_list_author_update', methods: ['GET'])]
    public function updateBooksCategoryByAuthor($category, BookRepository $bookRepository): Response
    {
        $bookRepository->updateBooksCategoryByAuthor($category);
        return $this->render('book/listBookAuthor.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }
    //DQL: Question 1
    #[Route('/book/NbrCategory', name: 'book_Count')]
    function NbrCategory(BookRepository $repo)
    {
        $nbr = $repo->NbBookCategory();
        return $this->render('book/showNbrCategory.html.twig', [
            'nbr' => $nbr,
        ]);
    }

    //DQL: Question 2
    #[Route('/book/showBookTitle', name: 'book_showBookByTitle')]
    function showTitleBook(BookRepository $repo)
    {
        $books = $repo->findBookByPublicationDate();
        return $this->render('book/showBooks.html.twig', [
            'books' => $books,
        ]);
    }
}
